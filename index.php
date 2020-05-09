<?php
require('Connections/zkizblog.php'); 
if($isLog){
    header("location: /compose.php");
}
$main_block_limit = 10;
$side_block_limit = 8;

$getRenew 	= dbAr("
SELECT blogname,username, lastcpid, b.`datetime` as md
FROM zb_user a, zb_contentpages b
WHERE b.ownerid = a.id
AND a.blacklisted = 0
AND b.id = lastcpid
ORDER BY lastcpid DESC 
LIMIT 0 , $side_block_limit",1200);

$rbmems 	= dbAr("SELECT username,blogname,slogan 
FROM zb_user a, 
(SELECT ownerid, sum(views) as ce 
FROM zb_contentpages GROUP BY ownerid ORDER BY ce DESC LIMIT 10) b
WHERE a.id = b.ownerid
",1200);
$hot_topics = cacheGet("RB_HOT_TOPIC");


$tags       = dbAr("SELECT * FROM zm_tags ORDER BY timestamp DESC LIMIT 50",60); 

$getentries = dbAr("SELECT a.id, a.title, a.datetime, a.commentnum, b.username, b.blogname 
FROM zb_contentpages a, zb_user b 
WHERE isshow = 1 
AND a.ownerid = b.id 
AND a.id = b.lastcpid
AND b.blacklisted = 0 
order by a.id desc LIMIT $main_block_limit");

$noSidebar = true;
$htmltitle	= "Real Blog - 首頁 提供高質素免費BLOG 架設";

include(template('header')); 
include(template('index')); 
include(template('footer'));

