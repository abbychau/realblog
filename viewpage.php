<?php
require_once('include/common.php');


$gTid = intval($_GET['tid']);

$gUser = htmlentities($_GET['username']);
if ($_GET['username'] == "" && !isset($_POST["reply_blog"])) {
	if ($gTid != "") {
		$tempUser = dbRs("SELECT username FROM zb_user a, zb_contentpages b WHERE b.user_id = a.id AND b.id=$gTid");
		header("LOCATION: http://realblog.zkiz.com/$tempUser/$gTid");
		exit;
	} else {
		screenMessage("Error", "Wrong URL");
	}
}
$row_getpage = dbRow("SELECT * FROM zb_contentpages WHERE id = {$gTid}");
if (!$row_getpage['user_id']) {
	//screenMessage("Error","The post is not found or deleted.");
	header("location:https://articles.zkiz.com/?rbid=$gTid");
	exit;
}
if ($row_getpage['is_show'] == "-1") {
	screenMessage("This post is hidden", "The post is not open, it is hidden by the moderator.");
}
if ($row_getpage['is_show'] == "0") {
	screenMessage("This post is hidden", "The post is not open, it is hidden by the author.");
}
$blogInfo = dbRow("SELECT * FROM zb_user WHERE id = " . $row_getpage['user_id']);

$owner = $row_getpage['user_id'];
if (!$row_getpage) {
	screenMessage("Error", "This article had been deleted or not exists.");
}

$editFormAction = "/$gUser/$gTid";

if ($_GET['go'] == 'next') {
	$nextArticle = dbRow("SELECT id, title FROM zb_contentpages WHERE id > $gTid AND user_id = $owner ORDER BY id ASC LIMIT 1");
	header("location: /$gUser/{$nextArticle['id']}");
}

if ($_GET['go'] == 'prev') {
	$prevArticle = dbRow("SELECT id, title FROM zb_contentpages WHERE id < $gTid AND user_id = $owner ORDER BY id DESC LIMIT 1");
	header("location: /$gUser/{$prevArticle['id']}");
}

if (isset($_POST["cid"])) {
	$pcid = intval($_POST["cid"]);

	if (dbRs("SELECT user_id FROM zb_contentpages a, zb_comment b WHERE a.id = b.pageid AND b.id = $pcid") != $gId) {
		die("fail");
	}

	dbQuery("UPDATE zb_contentpages set comment_count = comment_count - 1 WHERE `id` = (SELECT pageid FROM zb_comment WHERE `id` = {$pcid})");
	dbQuery("DELETE FROM zb_comment WHERE `id` = {$pcid}");
	die("success");
}
if (isset($_POST["key"]) && isset($_POST["pcid"])) {
	$pcid = intval($_POST["pcid"]);
	$arrayKey = intval($_POST["key"]);

	if (dbRs("SELECT user_id FROM zb_contentpages a, zb_comment b WHERE a.id = b.pageid AND b.id = $pcid") != $gId) {
		die("fail");
	}

	$comment = dbRs("SELECT comment FROM zb_comment WHERE id = {$pcid}");
	$comments = unserialize($comment);
	unset($comments[$arrayKey]);
	dbQuery("UPDATE zb_comment SET comment = :comment WHERE `id` = {$pcid}",['comment'=>serialize($comments)]);
	die("success");
}


if ($_POST["reply_blog"] == "1") {
	if ($blogInfo["login_to_comment"] == 1 && !$isLog) {
		screenMessage("Error", "You must login to comment in this blog.");
	}
	if ($owner != $gId) {
		$code = $_POST['g-recaptcha-response'];
		//$_POST['g-recaptcha-response'] / RECAPTCHA_V2_KEY / getIP()
		$arrQuery = ["secret" => RECAPTCHA_V2_KEY, "response" => $_POST['g-recaptcha-response'], "remoteip" => getIP()];
		$google_check_json = file_get_contents(
			"https://www.google.com/recaptcha/api/siteverify?" . http_build_query($arrQuery)
		);
		$result = json_decode($google_check_json, true);
	}
	if ($result["success"] != true && $owner != $gId) {
		$error = 1;
	}
	if ($error != 1) {
		//if(dbRs("SELECT count(1) FROM zb_user WHERE id=$owner
		if (!$isLog) {
			$verified = "0";
		} else {
			$verified = $_POST['name'] == $gUsername ? '1' : "0";
			$verified = $owner == $gId ? "2" : $verified;
		}

		dbQuery(
			"INSERT INTO zb_comment (name, email, content,is_whisper, `time`, pageid, verified, ip) VALUES 
				(:name, :email, :content, :is_whisper, NOW(), :tid, :verified, :ip)",
			[
				"name" => ($blogInfo["login_to_comment"] == 1 && $isLog) ? $gUsername : $_POST['name'],
				"email" => $_POST['email'],
				"content" => $_POST['content'],
				"is_whisper" => isset($_POST['is_whisper']) ? "1" : "0",
				"tid" => $_GET['tid'],
				"verified" => $verified,
				"ip" => getIP()
			]
		);

		dbQuery(sprintf("UPDATE zb_contentpages SET comment_count=comment_count+1 WHERE id=%s", $_GET['tid']));
		
		$safetitle = htmlentities($row_getpage['title']);

		$link = urlencode("http://realblog.zkiz.com/$gUser/$gTid");

		sendNotification($gUser, "<b>$gUsername</b> 對 <b>$safetitle</b> 做出回應", $link);
	} else {
		$failtext = htmlentities($_POST['content']);
	}
}

if ((isset($_POST["reply_reply"])) && ($_POST["reply_reply"] == "1")) {
	if ($blogInfo["login_to_comment"] == 1 && !$isLog) {
		screenMessage("Error", "You must login to comment in this blog.");
	}
	$postID = intval($_POST['id']);
	$postTID =  intval($_POST['tid']);
	$content = trim(htmlentities(str_replace(['"', "'"], "", $_POST['says'])));
	$username = trim(htmlentities($_POST['username']));


	if ($username == "") {
		screenMessage("ERROR", "Username is empty");
		exit;
	}
	if ($content == "") {
		screenMessage("ERROR", "Message is empty");
		exit;
	}

	$comment = dbRs("SELECT comment FROM zb_comment WHERE id = {$postID}");
	$comments = unserialize($comment);
	$comments[] = array(
		"username"	=> ($blogInfo["login_to_comment"] == 1 && $isLog) ? $gUsername : $username,
		"content"	=> $content,
		"timestamp"	=> time(),
		"isUser"	=> ($_POST['username'] == $gUsername) ? $isLog : false
	);
	$comment = serialize($comments);

	foreach ($comments as $v) {
		if ($v['isUser']) {
			$includeZids[] = $v['username'];
		}
	}

	if ($comments[0]['isUser'] == true) {
		$safetitle = htmlentities($row_getpage['title']);
		$link = urlencode("http://realblog.zkiz.com/$gUser/$gTid");
		sendNotifications($includeZids, "", "<b>$gUsername</b>在 <b>$safetitle</b> 對你做出的回應留言了。", $link);
	}

	dbQuery("UPDATE zb_comment SET comment = '$comment' WHERE id={$postID}");

	header("Location:" . prevURL());
	exit;
}


$comments = dbAr("SELECT * FROM zb_comment WHERE pageid = {$gTid} ORDER BY id");
//$thisType = dbRs("SELECT name FROM zb_contenttype WHERE id = {$row_getpage['type']}");

$getTypes = dbAr("SELECT count(*) as ce, b.id, b.user_id, b.name FROM zb_contentpages a, zb_contenttype b WHERE b.user_id = {$blogInfo['id']} AND a.content_type_id = b.id group by b.id");
foreach ($getTypes as $v) {
	if ($v["id"] == $row_getpage["type"]) {
		$thisType = $v["name"];
	}
}
$getPages = dbAr("SELECT * FROM zb_contentpages a WHERE user_id = {$blogInfo['id']} AND is_page=1");
// dbQuery("UPDATE zb_contentpages SET views = views + 1 WHERE id = $gTid");

$background = $blogInfo['background'];
$blogfooter = $blogInfo['footer'];
$sidebar = $blogInfo['sidebar'];


$pageinfos = dbAr("SELECT * FROM zb_contentpages WHERE user_id = ? AND is_show = 1 AND is_page = 0 ORDER BY id DESC LIMIT 10",$blogInfo['id'], 7200);

foreach ($pageinfos as $item) {
	//for sidebar.php
	$blogNewArticle[] = "<a href='/{$blogInfo['username']}/{$item['id']}'>{$item['title']}</a>";
}


if ($blogInfo['comment_system1'] == 1) {
	//get comments
	$recentComments = dbAr("SELECT b.id, SUBSTR(a.content,1,30) as conbar FROM zb_comment a, zb_contentpages b where b.is_show = 1 AND is_whisper = 0 AND a.pageid = b.id AND b.user_id = {$blogInfo['id']} ORDER BY a.time DESC LIMIT 15",[],60);

	//for sidebar.php
	foreach ($recentComments as $v) {
		$blogNewReply[] = "<a href='http://realblog.zkiz.com/{$blogInfo['username']}/{$v['id']}'>" . htmlspecialchars($v['conbar']) . "</a>";
	}
}
$z = dbAr("SELECT * FROM zb_contentpages WHERE user_id = {$blogInfo['id']} AND is_show = 1 AND is_page = 0 ORDER BY views DESC LIMIT 10",[], 7200);
//print_R($z);
//for sidebar.php
if ($z) {
	foreach ($z as $v) {
		$hotPost[] = "<a href='http://realblog.zkiz.com/{$blogInfo['username']}/{$v['id']}'>" . htmlspecialchars($v['title']) . "</a>";
	}
}

if ($blogInfo['comment_system1'] == 2) {



	//$request_url ="https://graph.facebook.com/comments/?ids={$url}";

	//$requests = file_get_contents_c($request_url,1200);

	//print_r($requests);
}


$gNavTitle = $blogInfo['blogname'];
$keywords = $row_getpage['password'] ? "Password Protected" : $blogInfo['username'] . ", " . $thisType . ", " . $row_getpage['title'];
$htmltitle = $row_getpage['title'] . " - " . $blogInfo['blogname'] . " - " . $thisType . " - " . "Real Blog";

$full = trim(strip_tags($row_getpage['content']));
$nulls = array(" ", "\n", "\r", "　", "\t", "&nbsp;", ">", "<", "/", "\\");
$description = $row_getpage['password'] ? "Password Protected" : mb_substr(str_replace($nulls, "", $full), 0, 200, "utf8") . "...";
$rsslink = "http://realblog.zkiz.com/rssdata/" . $blogInfo['id'] . ".xml";
$thistags = getTags($gTid, 2);

$mainTemplate = 'viewpage';



include(template("blogframe_{$blogInfo['blogframe']}"));
