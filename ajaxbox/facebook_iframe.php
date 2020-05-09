<?php
	require('../Connections/zkizblog.php'); 
	
	$fb = getFacebook();
	
?>
<html><head></head><body style="font-family:Arial;font-size:12px">
<?if($fb['me']){?>
	己用<a href='<?=$fb['me']['link'];?>'><strong><?=$fb['me']['name'];?></strong></a><br />進行Facebook 連結。
<?}else{?>
	<a href='<?=$fb['object']->getLoginUrl(array('scope' => 'read_stream, friends_likes'));?>'>連結到Facebook(不會重新整理)</a>
<?php } ?>
</body></html>