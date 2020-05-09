<?php 
require_once('../Connections/zkizblog.php'); 
if(!$isLog){die ("Please Login");}
$tid = intval($_GET['tid']);
if($tid==0){die("Access Denied.");}

$numArticle = dbRs("SELECT count(*) FROM zb_contentpages WHERE type = $tid");
if ($numArticle>0){die("This category has $numArticle articles left, deletion is not allowed.");}

$owner_id = dbRs("SELECT ownerid FROM zb_contenttype WHERE id = $tid");
if($owner_id != $gId){die('Access Denied');}
dbQuery("DELETE FROM zb_contenttype WHERE id=$tid");
die("Deleted.");

