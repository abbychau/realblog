<?php 
require_once('include/common.php'); 
$loginId = dbRs("SELECT id FROM zb_user WHERE username='$gUsername'");
$row_rs = dbRow("SELECT id, username, password, email FROM zm_members WHERE username='$gUsername'");
if(!$loginId){
	$blogname = $row_rs['username']."的博客";
	$id = dbQuery("INSERT INTO zb_user (username, password, email, blogname) VALUES ('{$row_rs['username']}', '{$row_rs['password']}', '{$row_rs['email']}', '{$blogname}')");
	dbQuery("UPDATE zf_user SET is_rbenabled = 1 WHERE username = '{$row_rs['username']}'");
	dbQuery("INSERT INTO zb_contenttype (ownerid, name) VALUES ('$id', '預設分類')");
	header("Location: compose.php");
}else{
	die("Your RB account is already activated!");
}

?>
