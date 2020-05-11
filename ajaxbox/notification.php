<?php 
require_once('../include/common.php'); 

if (!$isLog){die("請先登入");}

$getNoti = dbAr("SELECT * FROM zm_notification WHERE zid = '$gUsername' AND `read` = 0");

if ($_GET["type"] == "clickthrough"){
	$getID = safe(base64_decode($_GET['id']));
	dbQuery("UPDATE zm_notification SET `read` = 1 WHERE link = '$getID' AND zid='$gUsername'");
	
	header("location:$getID");
}
if ($_GET["type"] == "clear"){
	dbQuery("UPDATE zm_notification SET `read` = 1 WHERE zid = '$gUsername'");
	header("location:".prevURL());
}
?>
<div style="max-height:400px;overflow-y:auto;overflow-x:hidden;">
<?php foreach($getNoti as $v) { ?>
    <a class="blocka" href="/ajaxbox/notification.php?type=clickthrough&id=<?=base64_encode($v['link']);?>"><?=$v['content'];?>(<?=timeago(strtotime($v['time']));?>)</a>
<?php } ?>
<?php if(sizeof($getNoti)==0){?>
沒有通知
<?php }else{ ?>
<a class="blocka" href="/ajaxbox/notification.php?type=clear">清除所有</a>
<?php } ?>
</div>