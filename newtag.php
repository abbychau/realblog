<?php
	require_once('include/common.php');
	//header("content-type:text/plain");
	$a = dbAr("SELECT id from zb_contentpages where ownerid = 656");
	
	foreach($a as $v){
		echo $v['id']." : ";
		print_r(getTags($v['id'],2));
		echo "<br />";
	}