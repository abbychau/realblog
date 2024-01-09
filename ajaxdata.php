<?php 
require_once('include/common.php'); 
if($_GET['type'] == "notify"){
echo "0";
}
if($_GET['type'] == "mytypes"){
if(!$isLog){return false;}
	$mytypes = dbAr("SELECT * FROM zb_contenttype WHERE user_id = $gId");
        foreach ($mytypes as $v){
		$str.=$v['name']."\n";
	}
	echo $str;
}
if($_GET['type']=="mytags"){
	if(!$isLog){return false;}
	$arr = dbAr("SELECT distinct tag FROM `zm_tags` a,zm_tags_entry b, zb_contentpages  c WHERE  a.id = b.tag_id and b.entry_id = c.id and b.entry_type = 2 and c.user_id = $gId AND abs(tag) = 0");
	echo json_encode($arr);
	exit;
}
if($_GET['action']=="newest_entry"){
	//if(!$isLog){return false;}
	if(intval($_GET['last_id'])){
		$id = intval($_GET['last_id']);
		$id_constraint = "AND a.`id` < {$id}";
	}
	$arr = dbAr("SELECT id as entry_id,title,content,create_time,user_id FROM `zb_contentpages` a WHERE is_show = 1 AND password ='' {$id_constraint} AND a.id not in (select id from zb_user WHERE blacklisted = 1) {$id_constraint} ORDER BY a.`id` DESC LIMIT 10");
	$rbUserInfo = cacheGet("RB_USER_INFO");
	//print_r($rbUserInfo);
	/*
	$arr = dbAr("SELECT a.id as entry_id, blogname, user_id,title,username as owner_name,create_time, content FROM `zb_contentpages` a , `zb_user` b WHERE a.user_id = b.id {$id_constraint} AND is_show = 1 AND blacklisted = 0 ORDER BY a.`id` DESC LIMIT 10",30);*/
	
	foreach($arr as $k=>$v){
		$arr[$k]['create_time'] = timeago(strtotime($arr[$k]['create_time']));
		$arr[$k]['content'] = mb_substr(strip_tags($arr[$k]['content']),0,140,"utf8");
		$arr[$k]['owner_name'] = $rbUserInfo[$arr[$k]['user_id']]['username'];
		$arr[$k]['blogname'] = $rbUserInfo[$arr[$k]['user_id']]['blogname'];
	}
	echo json_encode($arr,JSON_UNESCAPED_UNICODE+JSON_FORCE_OBJECT);
	exit;
}
