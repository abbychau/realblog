<?php
require('include/common.php'); 
if($isLog && !isset($_GET['home'])){
    header("location: /compose.php");
}
$main_block_limit = 10;
$side_block_limit = 8;

$getRenew 	= dbAr("
SELECT blogname,username, last_content_page_id, b.create_time as md
FROM zb_user a, zb_contentpages b
WHERE b.user_id = a.id
AND a.blacklisted = 0
AND b.id = last_content_page_id
ORDER BY last_content_page_id DESC 
LIMIT 0 , $side_block_limit",[],1200);

$rbmems 	= dbAr("SELECT username,blogname,slogan 
FROM zb_user a, (
    SELECT user_id, sum(views) as ce 
    FROM zb_contentpages GROUP BY user_id ORDER BY ce DESC LIMIT 10) b
WHERE a.id = b.user_id
",[],1200);
$hot_topics = cacheGet("RB_HOT_TOPIC");


//$tags       = dbAr("SELECT * FROM zm_tags ORDER BY timestamp DESC LIMIT 40",[],60); 

$getentries = dbAr("SELECT a.id, a.title, a.create_time, a.comment_count, b.username, b.blogname 
FROM zb_contentpages a, zb_user b 
WHERE is_show = 1 
AND a.user_id = b.id 
AND a.id = b.last_content_page_id
AND b.blacklisted = 0 
order by a.id desc LIMIT $main_block_limit");

$noSidebar = true;
$htmltitle	= "Real Blog - 首頁 提供高質素免費BLOG 架設";

include(template('header')); 
include(template('index')); 
include(template('footer'));

