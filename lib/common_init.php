<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/Hong_Kong');
include_once(dirname(__FILE__) . "/constants.php");
include_once(dirname(__FILE__) . "/functions.php");
include_once(dirname(__FILE__) . "/redis_cli.php");

spl_autoload_register(function ($className)
{
	$guess = EXTERNAL_LIB . "{$className}.php";
	// echo $guess;
	if (file_exists($guess)) {
		require_once $guess;
		return true;
	} else {
		//die($guess);
	}
	return false;
});

// if (!in_array(whatsMyIP(), ["222.166.4.73", "127.0.0.1", "::1", ""])) {}
// if (in_array($_SERVER['REMOTE_ADDR'], [])) {header("location: https://www.google.com/");exit();}

define("COMPOSER_VENDOR", dirname(__FILE__) . "/../../../vendor");
define("GCM_APIKEY", "AIzaSyAmYgcjnNXY7LJAHqKpt_z4wtR-QGnO124");
define("EXTERNAL_LIB", dirname(__FILE__) . "/external/");
define("RECAPTCHA_V2_KEY", "6LdZOgkTAAAAAHUnhpZszr2OzuNOLPVDz7EtK1sw");


//News / Stock
$public_post_type = [1 => '公告', 2 => '報章/雜誌', 3 => 'Blog', 4 => '招股書', 5 => 'D.Webb', 11 => '盈警', 12 => '盈喜', 13 => '第一季業績', 14 => '中期業績', 15 => '第三季業績', 16 => '年度業績', 17 => '收購', 18 => '出售', 19 => '董事變更', 10 => '其他公告', 50 => 'report'];

//Realforum / Realblog Globals
$blackwords = ["加賴", "聊天交友", "援交", "找女人", "好茶", "西門町", "外送", "高檔茶", "外約", "叫妹", "webs.com"];
$gUsername = "Anonymous";$gId = 733;$isLog = false;
$isMobile = false;


$queryRecord = [];
$redisRecord=[];

$_SERVER['REMOTE_ADDR'] = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];

if(!isset($ZG_NODB)){
	$dbh=dbConnect();
}
$redis=new redis_cli('127.0.0.1', 6379);//$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);
$redisNative = new Redis();
$redisNative->connect('127.0.0.1', 6379);

logStartTime();

//Check login
if (isset($_COOKIE['RB_Sess_Cookie']) && $_COOKIE['RB_Sess_Cookie'] != "" && !isset($ZG_DRY)) {

	$arr = json_decode(decrypt($_COOKIE['RB_Sess_Cookie']), true);
	$gId = intval($arr[0]);
	$gIP = $arr[1];

	if ($gId) {
		//if($gIP == getIP()){
		$my = dbRow("SELECT * FROM zb_user WHERE id = $gId");

		$isLog = ($my['id'] >= 1);
		if ($my['usertype'] == -1) {
			screenMessage("Error", "Your account had been suspended");
		}

		if ($isLog) {
			$gUsername = $my['username'];
			$gUserGroup = $my['usertype'];

			//$my['notification'] = dbRs("SELECT count(1) FROM zm_notification WHERE `read` = 0 AND zid = :username", ["username" => $my['username']], 600);
			//$my['unread_pm'] = dbRs("SELECT count(1) FROM zf_pm WHERE isread = 0 AND to_id = :gid AND del_receiver = 0", ['gid' => $gId], 600);
			if (file_exists(getAvatarRealPath($gUsername, 150))) {
				$my['pic'] = getAvatarURL($gUsername, 150);
			}
		}
		//}else{
		//$isLog = true;
		//logoutGlobal();
		//screenMessage("你已經登出", "因為IP 轉變, 為了你的安全, 請重新登入", "/index.php");
		//}

	}
}
// if (!$isLog || $gId != 1) {}
