<?php

function sendGCM($regIDs, $message)
{
	$data = array('message' => $message);
	$url = 'https://android.googleapis.com/gcm/send';
	// $regIDs : array("asdfasdfasdf","1asfdfas89fvh9"....)
	$fields = array(
		'registration_ids'  => $regIDs,
		'data'              => $data,
	);

	$headers = array(
		'Authorization: key=' . GCM_APIKEY,
		'Content-Type: application/json'
	);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	curl_close($ch);
}
//timer
function logStartTime()
{
	global $start;
	$time = explode(' ', microtime());
	$time = $time[1] + $time[0];
	$start = $time;
}
function logEndTime()
{
	global $start;
	$time = explode(' ', microtime());
	$time = $time[1] + $time[0];
	return round(($time - $start), 4) * 1000;
}

function encrypt($data, $key = '000011110000aaaa0000cccc')
{
	$l = strlen($key);
	if ($l < 16)
		$key = str_repeat($key, ceil(16 / $l));

	if ($m = strlen($data) % 8)
		$data .= str_repeat("\x00",  8 - $m);

	$val = openssl_encrypt($data, 'BF-ECB', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);

	return trim(bin2hex($val));
}

function decrypt($data, $key = '000011110000aaaa0000cccc')
{
	if (trim($data) == "") {
		return "";
	}
	$l = strlen($key);
	if ($l < 16)
		$key = str_repeat($key, ceil(16 / $l));

	$data = @hex2bin(trim($data));
	$val = openssl_decrypt($data, 'BF-ECB', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);
	return trim($val);
}

function timeago($referencedate = 0, $timepointer = '', $measureby = '')
{

	// Measureby can be: s, m, h, d, or y

	if ($timepointer == '') $timepointer = time();
	$Raw = $timepointer - $referencedate; ## Raw time difference
	$Clean = abs($Raw);

	if ($Clean > 60 * 60 * 24 * 30) {
		if (date("Y") == date("Y", $referencedate)) {
			$strTime = date("n月j日", $referencedate);
		} else {
			$strTime = date("Y年n月j日", $referencedate);
		}
		return $strTime;
	}

	$calcNum = array(
		array('s', 60),
		array('m', 60 * 60),
		array('h', 60 * 60 * 60),
		array('d', 60 * 60 * 60 * 24),
		array('mo', 60 * 60 * 60 * 24 * 30)
	);
	## Used for calculating
	$calc = array(
		's' => array(1, '秒'),
		'm' => array(60, '分鐘'),
		'h' => array(60 * 60, '小時'),
		'd' => array(60 * 60 * 24, '日'),
		'mo' => array(60 * 60 * 24 * 30, '個月')
	);

	if ($measureby == '') {
		$usemeasure = 's'; ## Default unit
		for ($i = 0; $i < count($calcNum); $i++) {
			if ($Clean <= $calcNum[$i][1]) {
				$usemeasure = $calcNum[$i][0];
				$i = count($calcNum);
			}
		}
	} else {
		$usemeasure = $measureby; ## Used if a unit is provided
	}

	$datedifference = floor($Clean / $calc[$usemeasure][0]);

	if ($referencedate != 0) {
		return $datedifference . '' . $calc[$usemeasure][1] . '前';
	} else {
		return 'No Time';
	}
}



//CACHING

function cacheSet($key, $value, $expires = 0)
{
	global $redis, $cacheno;

	$redis->setValue($key, json_encode($value));
	if ($expires) {
		$redis->setTimeout($key, $expires);
	}
	//$hash = md5($key);
	//$time = time();
	//$cacheno++;
	//$strvalue = addslashes(serialize($value));

	//$skey = safe($key);
	//return dbQuery("INSERT INTO `real_cache` (`hash`,`value`,`timestamp`,`hint`) VALUES ('$hash','$strvalue','$time','$skey') ON DUPLICATE KEY UPDATE `value` = '$strvalue', `timestamp` = '$time'");
}
function cacheGet($key)
{
	global $redis, $redisRecord;
	$redisRecord[logEndTime().""] = [$key];
	return $redis->getValue($key);
	/*
			$hash = md5($key);
			$value = dbRs("SELECT `value` FROM `real_cache` WHERE hash = '$hash'");
			if(!$value){return false;}
			$cacheno++;
			return unserialize($value);
		*/
}
function cacheValid($key, $cachetime = 0)
{
	global $redis, $cacheno;
	return $redis->exists($key);
	/*
			$hash = md5($key);
			$timestamp = dbRs("SELECT `timestamp` FROM `real_cache` WHERE hash = '$hash'");
			$cacheno++;
			if($timestamp == false || time() > $cachetime + $timestamp){
			return false;
			}else{
			return true;
			}
		*/
}
function cacheVoid($key)
{
	global $redis;
	$redis->delete($key);
	/*
			$hash = md5($key);
			$value = dbQuery("DELETE FROM `real_cache` WHERE hash = '$hash'");
			$cacheno++;
			return unserialize($value);
		*/
}




function writeSessionStorage($key, $value)
{
	global $my;
	$arr = array();
	if ($my['session_storage'] == "") {
		$arr[$key] = $value;
		$data = safe(serialize($arr));
	} else {
		$originalData = unserialize($my['session_storage']);
		$originalData[$key] = $value;
		$data = safe(serialize($originalData));
	}

	dbQuery("UPDATE zf_user SET `session_storage` = '$data' WHERE id = {$my['id']}");
}
function getSessionStorage($key)
{
	global $my;
	if ($my['session_storage'] == "") {
		return false;
	}
	$arr = unserialize($my['session_storage']);
	if (array_key_exists($key, $arr)) {
		return $arr[$key];
	} else {
		return false;
	}
}
/*
		function cacheSet($key,$value) {
		file_put_contents(cachePath($key),serialize($value));
		}
		function cacheGet($key) {
		global $cacheno;
		$cacheno++;
		return unserialize(file_get_contents(cachePath($key)));
		}
		function cacheValid($key,$cachetime = 0) {
		return (file_exists(cachePath($key)) && (filemtime(cachePath($key)) > time() - $cachetime));
		}
		function cacheVoid($key) {
		unlink(cachePath($key));
		}
		function cachePath($key){
		$key = strlen($key)>32 ? md5($key) : $key;
		
		$dir = "/home/zkizcom78f/domains/zkiz.com/public_html/lib/temporary/".$key[0]."/".$key[1]."/".$key[2]."/";
		
		if(!is_dir($dir)){
		mkdir($dir,7777,true);
		}
		
		return $dir.$key;
	}*/

//NETWORK
function file_get_contents_c($url, $time = 0)
{
	$thiskey = "FGC_CACHE_" . $url;
	if ($time == 0) {
		return file_get_contents($url);
	} else {
		if (cacheValid($thiskey, $time)) {

			return cacheGet($thiskey);
		} else {
			$content = file_get_contents($url);
			cacheSet($thiskey, $content, $time);
			return $content;
		}
	}
}
function getIP()
{
	$keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
	foreach ($keys as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
				if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
					return $ip;
				}
			}
		}
	}
}
function prevURL()
{
	return $_SERVER["HTTP_REFERER"];
}
function curURL()
{
	$pageURL = (isset($_SERVER['HTTPS'])&&$_SERVER["HTTPS"] == "on" ? 'https' : 'http');
	$pageURL .= "://";
	$pageURL .= $_SERVER["SERVER_NAME"];
	//		if($_SERVER["SERVER_PORT"] != "80"){
	//			$pageURL .= ":".$_SERVER["SERVER_PORT"];
	//		}
	$pageURL .= $_SERVER["REQUEST_URI"];
	return htmlspecialchars($pageURL);
}
function getSearchEngineQuery()
{
	$referrer = $_SERVER['HTTP_REFERER'];
	if (preg_match("/[\.\/](google|yahoo|bing|geegain|mywebsearch|ask|alltheweb)\.[a-z\.]{2,5}[\/]/i", $referrer, $search_engine)) {
		$referrer_query = parse_url($referrer);
		$referrer_query = $referrer_query["query"];
		$q = "[q|p]"; //Yahoo uses both query strings, I am using switch() for each search engine 
		preg_match("/" . $q . "=(.*?)&/", $referrer, $keyword);
		$keyword = urldecode($keyword[1]);
	}
	return $keyword;
}
function file_post_contents($url, $request_assoc = [])
{

	$opts = array('http' =>
	array(
		'method'  => 'POST',
		'header'  => 'Content-type: application/x-www-form-urlencoded',
		'content' => $request_assoc
	));

	$context  = stream_context_create($opts);

	return file_get_contents($url, false, $context);
}


//STRING
function right($value, $count)
{
	$value = mb_substr($value, -$count, strlen($value), 'utf-8');
	return $value;
}
function left($value, $count)
{
	$value = mb_substr($value, 0, $count, 'utf-8');
	return $value;
}
function properCase($words)
{
	$words = explode(" ", $words);
	$result = "";
	for ($i = 0; $i < count($words); $i++) {
		$s = strtolower($words[$i]);
		$s = substr_replace($s, strtoupper(substr($s, 0, 1)), 0, 1);
		$result .= "$s ";
	}
	return trim($result);
}
function assoc2index($array, $col1, $col2)
{
	foreach ($array as $v) {
		$tmp[$v[$col1]] = $v[$col2];
	}
	return $tmp;
}
function generatePagin($pageNo, $base, $queryVariableName, $totalPages)
{
	pagin($pageNo, $base, qryStrE($queryVariableName, $_SERVER['QUERY_STRING']), $totalPages);
}
function pagin($pageNo, $base, $queryString, $totalPages)
{
	if (left($queryString, 5) == "&amp;") {
		$queryString = mb_substr($queryString, 4);
	}
	if (left($queryString, 1) == "&") {
		$queryString = mb_substr($queryString, 1);
	}
	$queryString = htmlentities($queryString, ENT_QUOTES);
	if ($totalPages <= 0) {
		return false;
	}

	echo '<ul class="pagination" style="margin: 0">';
	if ($pageNo > 0) {
		$d = $pageNo - 1;
		echo "<li><a href='$base?page=0&amp;$queryString'>&laquo;</a></li>";
	} else {
		echo "<li class='disabled'><a href='#'>&laquo;</a></li>";
	}

	$from = max(0, $pageNo - 5);
	$to = min($pageNo + 5, $totalPages);

	if (0 < $pageNo - 5) {
		echo " <li class='disabled'><a href='#'>...</a></li> ";
	}

	for ($i = $from; $i <= $to; $i++) {

		if ($i == $pageNo) {
			echo "<li class='active'><a href='#'>" . ($i + 1) . "<span class='sr-only'>(current)</span></a></li>";
		} else {
			echo "<li><a href='$base?page=$i&amp;$queryString'>" . ($i + 1) . "<span class='sr-only'></span></a></li>";
		}
	}

	if ($totalPages > $pageNo + 5) {
		echo " <li class='disabled'><a href='#'>...</a></li></li>";
	}

	if ($pageNo < $totalPages) {
		$b = $pageNo + 1;
		echo "<li><a href='$base?page=$totalPages&amp;$queryString'>&raquo;</a></li>";
	} else {
		echo "<li class='disabled'><a href='#'>&raquo;</a></li>";
	}
	echo "</ul>";
}
function qryStrE($strExcept, $strInput)
{

	parse_str($strInput, $arrTmp);
	if (sizeof($arrTmp) > 0) {
		unset($arrTmp[$strExcept]);

		foreach ($arrTmp as $k => $v) {
			if (is_array($k)) {
				$k = $k[0];
			}
			if (is_array($v)) {
				$v = $v[0];
			}
			$arrQueryString[] = htmlentities($k) . "=" . htmlentities($v);
		}



		return $arrQueryString ? implode("&amp;", $arrQueryString) : "";
	} else {
		return "";
	}
}
function urlExcept($except)
{
	return str_replace("&amp;", "", qryStrE($except, curURL()));
}
function shortenText($text, $max = 40)
{
	return mb_strlen($text, 'utf-8') > $max ? (left($text, round($max / 2 - 1)) . " ... " . right($text, round($max / 2 - 1))) : $text;
}
function isRussian($text)
{
	return preg_match('/[А-Яа-яЁё]/u', $text??'');
}
function isJapanese($word)
{
	return preg_match('/[\x{4E00}-\x{9FBF}\x{3040}-\x{309F}\x{30A0}-\x{30FF}]/u', $word);
}
function symbolize($num)
{
	return sprintf("%04d", $num); //.".HK"
}
function str_n2w($strs, $types = '0')
{  // narrow to wide , or wide to narrow
	$nt = array(
		"(", ")", "[", "]", "{", "}", ".", ",", ";", ":",
		"-", "?", "!", "@", "#", "$", "%", "&", "|", "\\",
		"/", "+", "=", "*", "~", "`", "'", "\"", "<", ">",
		"^", "_",
		"0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
		"a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
		"k", "l", "m", "n", "o", "p", "q", "r", "s", "t",
		"u", "v", "w", "x", "y", "z",
		"A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
		"K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
		"U", "V", "W", "X", "Y", "Z",
		" "
	);
	$wt = array(
		"（", "）", "〔", "〕", "｛", "｝", "﹒", "，", "；", "：",
		"－", "？", "！", "＠", "＃", "＄", "％", "＆", "｜", "＼",
		"／", "＋", "＝", "＊", "～", "、", "、", "＂", "＜", "＞",
		"︿", "＿",
		"０", "１", "２", "３", "４", "５", "６", "７", "８", "９",
		"ａ", "ｂ", "ｃ", "ｄ", "ｅ", "ｆ", "ｇ", "ｈ", "ｉ", "ｊ",
		"ｋ", "ｌ", "ｍ", "ｎ", "ｏ", "ｐ", "ｑ", "ｒ", "ｓ", "ｔ",
		"ｕ", "ｖ", "ｗ", "ｘ", "ｙ", "ｚ",
		"Ａ", "Ｂ", "Ｃ", "Ｄ", "Ｅ", "Ｆ", "Ｇ", "Ｈ", "Ｉ", "Ｊ",
		"Ｋ", "Ｌ", "Ｍ", "Ｎ", "Ｏ", "Ｐ", "Ｑ", "Ｒ", "Ｓ", "Ｔ",
		"Ｕ", "Ｖ", "Ｗ", "Ｘ", "Ｙ", "Ｚ",
		"　"
	);

	if ($types == '0') {
		// narrow to wide
		$strtmp = str_replace($nt, $wt, $strs);
	} else {
		// wide to narrow
		$strtmp = str_replace($wt, $nt, $strs);
	}
	return $strtmp;
}
function safe($theValue)
{
	if (is_array($theValue)) {
		$theValue = reset($theValue);
	}

	$theValue = stripslashes($theValue);
	//$theValue = str_replace(array("𥧌", "𠝹","𡃁","𨋢","𠵱","𥄫","𠽌","唧","𠱁","𡁻","埸"),array("峰","界","僆","升降機","而","目及","雪","即","氹","趙","場"),$theValue);

	$theValue = addslashes($theValue);
	return $theValue;
}
function htmlSafe($txt)
{
	$txt = htmlentities($txt);
	$txt = strip_tags($txt);
	return $txt;
}
function wiki_convert($string)
{
	// [url] 超連結
	$string = strip_tags($string, "<p><br><a><div><h1><h2><h3><h4><hr><font><span><table><tr><td><img><ul><ol><li>");
	$string = preg_replace('/\[\[(.*?)\]\]/is', '<a href="http://wiki.zkiz.com/$1" style="font-weight:bold">$1</a>', $string);
	return $string;
}
//DATABASE
//error handler
function dbErr($msg, $qry)
{
	$str = "Error: ";
	$str .= "$msg ";
	$str .= "$qry .";
	$x = 0;
	foreach (debug_backtrace() as $k) {
		$str .= "#" . $x++ . $k['file'] . ":" . $k['line'] . "->" . $k['function'] . "(" . implode(",", $k['args']) . ") ";
	}

	error_log($str . curURL() . pathinfo(__FILE__, PATHINFO_FILENAME));
	//file_put_contents("sql_err",$msg."\t".$qry,FILE_APPEND);
	return $str;
}
// 	function dbConnect(){ 
// 		global $queryRecord,$conn;
// 		$conn = @mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);

// 		if(!$conn) {
// error_log("DB Cannot Connect");
// 			header('HTTP/1.1 500 Internal Server Error');
// 			//die("ZKIZ.com is under maintainance... ".'<br />You can chat for a little while here and contact abbychau!<br /><script type="text/javascript" src="http://www5.yourshoutbox.com/shoutbox/start.php?key=595986227"></script>');
// 			//file_put_contents("mysql_down","1");
// 			exit;
// 		}

// 		//mysql_query("SET NAMES UTF8");
// 		mysqli_select_db($conn,DB_NAME); 
// 	}
function dbConnect()
{
	// define("DB_HOSTNAME", "localhost");
	// define("DB_USERNAME", "zkizcom78f");
	// define("DB_NAME", "zkizcom78f");
	// define("DB_PASSWORD", "aassddff");
	$dbh = new PDO(
		"mysql:dbname=" . DB_NAME . ";host=" . DB_HOSTNAME . "", 
		DB_USERNAME, 
		DB_PASSWORD,
		[
			PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES=> false
		]
	);
	return $dbh;
}
// function dbAr($query, $cachetime=0){

// 	global $queryRecord,$conn;

// 	//log attacks
// 	if(trim($query)==""){
// 		return [];
// 	}
// 	if(stristr($query,"0x716a6a6a71")){
// 		//dbQuery("INSERT INTO zm_logging SET query = '".curURL()." : $query'");
// 		exit;
// 	}
// 	if(stristr($query,"0x7170717071")){
// 		//dbQuery("INSERT INTO zm_logging SET query = '".curURL()." : $query'");
// 		exit;
// 	}		


// 	if( $cachetime > 0 ){

// 		if (cacheValid($query)) {
// 			$res = cacheGet($query);
// 			}else{
// 			$res = dbAr($query);
// 			cacheSet($query,$res,$cachetime);
// 		}
// 		return $res;
// 	}
// 	$arr = array();
// 	$queryRecord[logEndTime()] = [$query,$cachetime];
// 	$tmp = mysqli_query($conn,$query) or die(dbErr(mysqli_error($conn),$query));
// 	if(mysqli_num_rows($tmp)==0){

// 		return $arr;

// 	} else {
// 		while($row = mysqli_fetch_assoc($tmp)){
// 			$arr[] = $row;
// 		}
// 		mysqli_free_result($tmp);
// 		return $arr;
// 	}
// }
// function dbRow($query, $cachetime=0){
// 	$Ar = dbAr($query,$cachetime);
// 	if(is_array($Ar) && sizeof($Ar)>0){
// 		return reset($Ar);
// 		}else{
// 		return [];
// 	}
// }
// function dbRs($query, $cachetime=0){
// 	$Ar = dbRow($query,$cachetime);
// 	if(is_array($Ar) && sizeof($Ar)>0){
// 		return reset($Ar);
// 		}else{
// 		return null;
// 	}
// }
// function dbQuery($query,$option=[]){

// 	global $conn,$queryRecord;
// 	$tmp = mysqli_query($conn,$query,$option["async"]?MYSQLI_ASYNC:MYSQLI_STORE_RESULT) or die(dbErr(mysqli_error($conn),$query));;
// 	$queryRecord[logEndTime()] = [$query];


// 	if(stristr($query,"zm_tags") && (stristr($query,"insert") || stristr($query,"delete") || stristr($query,"update"))){
// 		$query = safe($query);
// 		//mysqli_query("INSERT INTO zm_logging SET query = '$query'");
// 	}




// 	return mysqli_insert_id($conn);
// }
function dbQuery($query, $param = [])
{
	global $dbh;
	dbAr($query, $param, 0);
	return $dbh->lastInsertId();
}
function dbAr($query, $param = [], $cachetime = 0)
{

	global $dbh, $queryRecord;

	if ($param != [] && !is_array($param)) {
		$param = [$param];
	}

	$queryRecord[] = [$query, $param];
	if ($cachetime > 0) {
		$cacheKey = "ZKIZ:SQL_CACHE:".$query . json_encode($param);
		if (cacheValid($cacheKey)) {
			$res = cacheGet($cacheKey);
		} else {
			$res = dbAr($query, $param);
			cacheSet($cacheKey, $res, $cachetime);
		}
		return $res;
	}

	if (is_array($param) && sizeof($param) > 0) {
		$stmt = $dbh->prepare($query);

		$stmt->execute($param);
	} else {
		$stmt = $dbh->query($query);
	}
	if($stmt == null){
		return [];
	}

	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$x = [];

	foreach ($stmt as $v) {
		$x[] = $v; //clone
	}
	return $x;
}
function dbRow($query, $param = [])
{
	$x = dbAr($query, $param);
	return isset($x[0]) ? $x[0] : [];
}
function dbRs($query, $param = [])
{
	$x = dbRow($query, $param);
	return reset($x);
}



//DATATYPE
function groupAssoc($assoc, $key)
{
	$tmp = array();
	foreach ($assoc as $v) {
		$tmp[] = $v[$key];
	}
	return $tmp;
}

function sendNotifications($includeZids, $field, $message, $link)
{
	global $gUsername, $blackwords;
	$i = 0;
	if (!$message) {
		return false;
	}
	$message = trim($message);
	if (str_ireplace($blackwords, '', $message) != $message || str_ireplace($blackwords, '', $message) != $message) {
		return false;
	}
	$link = trim($link);
	foreach ($includeZids as $v) {
		if ($field != "") {
			$toName = $v[$field];
		} else {
			$toName = $v;
		}
		//$toName = trim($toName,"'");
		$strAdd = [];
		$questions = [];
		if (($toName != $gUsername && $toName != "Anonymous") || stristr($link, "news.php")) {
			$strAdd = array_merge($strAdd, [$toName, $message, $link]);
			$questions[] = "(?,CURRENT_TIMESTAMP,?,?)";
			++$i;
		}
		if ($i == 200) {
			break;
		}
	}
	if (sizeof($strAdd) > 0) {
		dbQuery(
			"INSERT INTO `zm_notification` (`zid`, `time`, `content`, `link`) VALUES " . implode(",", $questions),
			$strAdd
		);
	}
}
function sendNotification($zid, $message, $link)
{
	global $blackwords;
	if (!$message) {
		return false;
	}
	if (str_ireplace($blackwords, '', $message) != $message || str_ireplace($blackwords, '', $message) != $message) {
		return false;
	}
	dbQuery("INSERT INTO `zm_notification` (`zid`, `time`, `content`, `link`) VALUES (?, CURRENT_TIMESTAMP, ?, ?)", [$zid, $message, $link]);
}
function useMoney($amount, $zid = -1, $score = 'score1')
{
	global $my;
	global $isLog;
	if (!$isLog) {
		screenMessage("未登入", "你需要先登入才可以進作操作。");
	}

	if ($zid == -1) {
		$zid = $my['id'];
	}

	$owned = dbRs("SELECT $score FROM zf_user WHERE id = $zid");
	if ($owned > $amount) {
		dbQuery("UPDATE zf_user SET $score = $score - $amount WHERE id = $zid");
		$my[$score] = $my[$score] - $amount;
	} else {
		screenMessage("分數不足", "你需要 $amount 以進行此操作，但你只有 $owned 。");
		exit;
	}
}
function addMoney($amount, $zid, $score = 'score1')
{
	global $my, $isLog;
	if ($isLog) {
		dbQuery("UPDATE zf_user SET $score = $score + $amount WHERE id = $zid");
		$my[$score] = $my[$score] + $amount;
		return true;
	} else {
		return false;
	}
}

function loginRealBlog($username,$password){
	
	// login using zb_user
	$username = trim($username);

	if($username == "" || $password == ""){return false;}
	// print_r([
	// 	$username, 
	// 	$password
	// ]);
	$memInfo = dbRow("SELECT * FROM zb_user WHERE username = ? AND password = ?", [
		$username, 
		$password
	]);
	if (sizeof($memInfo) == 0) {
		return false;
	}

	setcookie('RB_Sess_Cookie', encrypt(
		json_encode([$memInfo['id'], getIP()])
	), time() + 1800000, "", "realblog.zkiz.com");

	return true;
}

function logoutGlobal($doRedirect = false)
{
	setcookie('RF_Sess_Cookie', "", time() - 3600, "", ".zkiz.com");
	setcookie('prevScore1', "", time() - 3600, "", ".zkiz.com");

	if ($doRedirect) {
		if ($_SERVER['HTTP_REFERER'] != "") {
			header("location:" . $_SERVER['HTTP_REFERER']);
			exit;
		} else {
			header("location: index.php");
			exit;
		}
	}
}
function addNews($username, $content, $componentid, $link)
{
	// 1:rf 2:rb 3:ec 4:zfreply 0:directspeak
	$content = safe($content);
	dbQuery("INSERT INTO `zm_news` (`id` ,`username` ,`content` ,`componentid` ,`link`)VALUES(NULL, '$username',  '$content',  '$componentid',  '$link')");
}
function screenMessage($title, $message, $url = "", $errorCode = "")
{
	global $ttl, $msg, $defaulturl, $gId, $isLog;

	$ttl = $title;
	$msg = $message;
	$defaulturl = $url;
	if ($errorCode != "") {
		if ($errorCode == 404) {
			header('HTTP/1.1 404 Not Found', true, 404);
		}
	}
	include(dirname(__FILE__) . "/message.php");
	exit;
}


//
function getAvatarRealPath($username, $size)
{
	//size:200,150,100,50

	$file = base64_encode($username) . "_" . $size . ".jpg";
	return dirname(__FILE__) . "/../members.zkiz.com/avatars/{$file}";
}
function getAvatarURL($username, $size)
{
	//size:200,150,100,50

	$file = base64_encode($username) . "_" . $size . ".jpg";
	return "http://members.zkiz.com/avatars/{$file}";
}
function isAvatarSet($username)
{
	return file_exists(getAvatarRealPath($username, 150));
}


//COLORS
function hsl2rgb($H, $S, $V)
{
	$H *= 6;
	$h = intval($H);
	$H -= $h;
	$V *= 255;
	$m = $V * (1 - $S);
	$x = $V * (1 - $S * (1 - $H));
	$y = $V * (1 - $S * $H);
	$a =
		[
			[$V, $x, $m], [$y, $V, $m],
			[$m, $V, $x], [$m, $y, $V],
			[$x, $m, $V], [$V, $m, $y]
		][$h];
	return sprintf("#%02X%02X%02X", $a[0], $a[1], $a[2]);
}

function hue($tstr)
{
	return unpack('L', hash('adler32', $tstr, true))[1];
}

function str2color($str)
{
	$h = hue(md5($str));
	return hsl2rgb($h / 0xFFFFFFFF, 0.7, 0.7);
}


//TAGS	
function getNativeTags($str)
{
	return [];
	global $so, $translate;
	if (!$translate) {
		$translate = new TranslateChinese();
	}
	if (!$so) {
		$so = scws_new();
	}

	$so->set_charset('utf-8');
	//設置分詞所用詞典(此處使用utf8的詞典)
	$so->set_dict('/home/wwwroot/dict_cht.utf8.xdb');
	//設置分詞所用規則
	$so->set_rule('/home/wwwroot/rules_cht.utf8.ini');
	//分詞前去掉標點符號
	$so->set_ignore(true);
	//是否複式分割，如「中國人」返回「中國＋人＋中國人」三個詞。
	$so->set_multi(true);
	//設定將文字自動以二字分詞法聚合
	$so->set_duality(true);
	//$so->set_traditional(true);
	//要進行分詞的語句
	$so->send_text($translate->trad($str));
	//獲取分詞結果，如果提取高頻詞用get_tops方法
	while ($tmp = $so->get_result()) {

		foreach ($tmp as $word) {
			if (mb_strlen(trim($word['word']), "utf-8") > 1) {
				$tags[] = $word['word'];
			}
		}
	}
	if ($tags) {
		$tags = array_unique($tags);
		return $tags;
	} else {
		return [];
	}
}

function removebs($str)
{
	$strarr = str_split($str);
	$newstr = '';
	foreach ($strarr as $char) {
		$charno = ord($char);
		if ($charno == 163) {
			$newstr .= $char;
			continue;
		} // keep £ 
		if ($charno > 31 && $charno < 127) {
			$newstr .= $char;
		}
	}
	return $newstr;
}
function properTag(&$tag)
{
	// global $translate;
	// if (!$translate) {
	// 	$translate = new TranslateChinese();
	// }
	$tag = str_replace(['　', "\t"], '', $tag);
	//$tag = properCase($tag);
	$tag = trim($tag);
	$tag = trim($tag, "　\t `");
	$tag = trim($tag, '™½é¾çŽ,[] `@#$%^&*()_+-=|><{}/\\\'');
	$tag = str_n2w($tag, 1);

	//$tag = removebs($tag);
	if (is_numeric($tag) && strlen($tag) < 4) {
		$tag = sprintf("%04d", $tag);
	}

	// $tag = $translate->trad(safe($tag));

	if (mb_strlen($tag, 'utf8') > 15) {
		$tag = "";
	}
	if (isRussian($tag)) {
		$tag = "";
	}
}
function insertTag($tag, $entry_id, $entry_type)
{

	if (is_array($tag)) {
		foreach ($tag as $v) {
			insertTag($v, $entry_id, $entry_type);
		}
	} else {
		properTag($tag);

		if ($tag == "") {
			return false;
		}
		dbQuery("INSERT IGNORE INTO zm_tags (tag,searched_count) VALUES (:tag,1)", ['tag' => $tag]);
		dbQuery(
			"INSERT IGNORE INTO zm_tags_entry SET tag_id = (
				SELECT id FROM zm_tags WHERE tag = :tag
			), entry_type = :entry_type, entry_id=:entry_id;",
			['tag' => $tag, 'entry_type' => $entry_type, 'entry_id' => $entry_id]
		);
		return true;
	}
}
function getTags($tid, $entry_type = 1)
{
	$tid = intval($tid);
	$entry_type = intval($entry_type);
	$now_tags = dbAr("select t.tag FROM zm_tags t, zm_tags_entry et WHERE t.id = et.tag_id AND entry_id={$tid} AND entry_type={$entry_type}");
	$arrTags = array();
	foreach ($now_tags as $v) {
		$arrTags[] = $v['tag'];
	}

	return $arrTags;
}
function clearTag($entry_id, $entry_type)
{
	dbQuery("DELETE FROM zm_tags_entry WHERE entry_type = {$entry_type} AND entry_id={$entry_id}", true);
}
function getEntryByTag($tag, $entry_type, $limit = 50)
{
	properTag($tag);
	$ids = @dbAr("SELECT entry_id FROM zm_tags t, zm_tags_entry et WHERE t.id = et.tag_id AND tag= binary :tag AND entry_type = $entry_type ORDER BY entry_id DESC LIMIT $limit", ['tag' => $tag]);


	if ($ids) {
		foreach ($ids as $v) {
			$result[] = $v['entry_id'];
		}
		return $result;
	} else {
		return false;
	}
}
function getRelatedTag($tag)
{
	return [];
	properTag($tag);

	if ($tag == "") {
		return array();
	}

	$tag_ids = dbAr("SELECT tag FROM zm_tags_entry et, zm_tags t WHERE t.id = et.entry_id AND entry_id IN (
		
		(SELECT entry_id FROM zm_tags_entry et2, zm_tags t2 WHERE et2.tag_id = t2.id AND t2.tag='$tag')
		
		)");
	return $tag_ids;
}
function extractTagsFromString($str)
{

	return getNativeTags($str);

	$tmpTags = dbAr("SELECT tag FROM zm_tags");
	foreach ($tmpTags as $v) {
		if (mb_strlen($v['tag']) > 2 && stristr($str, $v['tag'])) {
			$store[] = $v['tag'];
		}
	}
	return $store;
}
//SocialMedia
function getFacebook()
{

	include_once(dirname(__FILE__) . "/external/facebook.php");
	$facebook = new Facebook(array(
		'appId'  => '255045087298',
		'secret' => 'd81e5a4fe63162338ed41b6783816e24',
		'cookie' => true, // enable optional cookie support
	));
	$user = $facebook->getUser();
	$fbme = null;

	if ($user) {
		try {
			//$uid = $facebook->getUser();
			$fbme = $facebook->api('/me');
		} catch (FacebookApiException $e) {
		}
	} else {
		$facebook->getLoginUrl();
	}

	return array('me' => $fbme, 'object' => $facebook);
}
function getPlurk($plurkkey)
{
	include_once(dirname(__FILE__) . "/external/plurk_api.php");
	$plurk = new plurk_api();

	list($api_key, $username, $password) = explode("|", $plurkkey);

	$login = $plurk->login($api_key, $username, $password);
	if ($login) {
		return $plurk;
	} else {
		return false;
	}
}
function send_no_cache_header()
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
}
function googleImageByKeyword($str)
{
	$str = urlencode($str);
	$jsrc = "http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q={$str}";
	//echo  $jsrc;
	$json = file_get_contents($jsrc, 60 * 60 * 24 * 60);
	//echo $json;
	$jset = json_decode($json, true);
	return $jset["responseData"]["results"][0]["url"];
}
function insertTagAndNotify($thisTag, $insert_id, $message, $type)
{
	if ($thisTag != "") {
		$arr = explode(",", $thisTag);
		foreach ($arr as $v) {
			if ($insert_id > 0) {
				insertTag($v, $insert_id, $type);
				continue;
				$number = intval($v);
				if (is_numeric($number)) {

					$zidVert = dbAr("SELECT username FROM zf_attention WHERE code = {$number}");

					if ($message != "") {

						$message = str_replace("%number%", $v, $message);
						$message = safe($message);

						$url = "http://www.zkiz.com/news.php?id={$insert_id}";
						if ($type == 2) {
							$url = "http://realblog.zkiz.com/viewpage.php?tid={$insert_id}";
						}
						if ($type == 1) {
							$url = "http://realforum.zkiz.com/thread.php?tid={$insert_id}";
						}
						sendNotifications($zidVert, 'username', $message, $url);
					}
				}
			}
		}
	}
}

//STOCK
function getStockInfo($arr)
{

	if (!is_array($arr)) {
		$arr = [$arr];
		$return_one = true;
	}
	$arrUncached = [];
	foreach ($arr as $tmpCode) {

		if (cacheValid("HK_STOCK:" . $tmpCode)) {
			$stockinfo[$tmpCode] = cacheGet("HK_STOCK:" . $tmpCode);
			if (!$stockinfo[$tmpCode]) {
				$arrUncached[] = str_replace(".HK", "", $tmpCode);
			}
		} else {
			$arrUncached[] = str_replace(".HK", "", $tmpCode);
		}
	}

	if (sizeof($arrUncached) > 0) {
		//$yahooArr = getStockInfoFromYahoo($arrUncached);

		$str_arrUncached = implode(",", $arrUncached);
		$max_date = dbRs("SELECT max(`date`) from stock_market_values");
		$info = dbAr("SELECT * FROM stock_market_values WHERE stock_code IN ($str_arrUncached) AND `date` = '$max_date'");
		$names = dbAr("SELECT english,chinese,code FROM stock_list WHERE code IN ($str_arrUncached)");
		foreach ($names as $name) {
			$nameDict[$name['code']] = $name['chinese'];
		}
		foreach ($info as $v) {
			if ($v['stock_code'] != "") {

				$tmp[$v['stock_code']] = $v;
				$tmp[$v['stock_code']]['name'] = $nameDict[$v['stock_code']];
				//					if($change && $lastprice && $name){
				//						cacheSet(
				//						"HK_STOCK:{$v['stock_code']}",
				//						$v,
				//						15*60
				//						);
				//					}
			}
		}
		$stockinfo = $stockinfo ? array_merge($tmp, $stockinfo) : $tmp;
	}
	$debug = $return_one ? $stockinfo[$arr[0]] : $stockinfo;
	// print_r($debug);
	return $debug;
}
function getStockInfoFromYahoo($arr)
{
	return;
	//nl1d1t1c1hgvj1k4k5m3p5ry
	//n:name
	//l1:lastprice
	//d1:lasttradedate
	//http://dirk.eddelbuettel.com/code/yahooquote.html

	$filecontent = file_get_contents_c("http://quote.yahoo.com/d/quotes.csv?f=nl1d1t1c1hgvj1k4k5m3p5ryb4sjk&e=.csv&s=" . implode(",", $arr), 15 * 60);

	$lines = explode("\n", $filecontent);
	foreach ($lines as $line) {
		$_i = 0;
		//list($name,$lastprice,$date,$time,$change,$high,$low,$volume,$marketcap,$yearchangeh,$yearchangel,$fifma,$psr,$per,$dividend,$bookvalue,$symbol)
		$arrDataline = str_replace("\"", "", explode(",", $line));
		$tmp = array_fill_keys(['name', 'lastprice', 'date', 'time', 'change', 'high', 'low', 'volume', 'marketcap', 'yearchangeh', 'yearchangel', 'fifma', 'psr', 'per', 'dividend', 'bookvalue', 'symbol', '52low', '52high'], 0);
		foreach ($tmp as $k => $v) {
			$tmp[$k] = $arrDataline[$_i++];
		}
		if ($tmp['symbol'] != "") {
			$stockinfo[$tmp['symbol']] = $tmp;
			if ($change && $lastprice && $name) {
				cacheSet(
					"HK_STOCK:{$symbol}",
					$tmp,
					15 * 60
				);
			}
		}
	}

	return $stockinfo;
}
function dbAffected()
{
	return "-3333";
}
function mysql_insert_id()
{
	global $dbh;
	return $dbh->lastInsertId();
}

function whatsMyIP()
{
	$keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
	foreach ($keys as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
				if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
					return $ip;
				}
			}
		}
	}
}