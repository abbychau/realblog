<?php 
require_once('include/common.php'); 
if(!$isLog){die ("Please Login");}
$gTid = intval($_GET['tid']);
$gDid = intval($_GET['did']);

if($gDid !=""){
	$expected = dbRs("SELECT ownerid FROM zb_contentpages WHERE id = {$gDid}");
	if(!$expected){
		screenMessage("Error", "Not found!");
		exit;
	}
	if($expected != $gId){
		screenMessage("Error", "Access Denied!" . $expected . "+" . $gId);
		exit;
	}

	dbQuery("DELETE FROM zb_contentpages WHERE id={$gDid}");

	$redisNative->hDel($rsskey,$gId);

    // cacheVoid($rsskey.$gId);
    //screenMessage("刪除完成","<a href='/$gUsername'>返回自己的Blog</a><br /><a href='modifyentryindex.php'>返回修改文章列表</a>");
	header("location: /modifyentryindex.php");
	exit;
}

$title = dbRs("SELECT title FROM zb_contentpages WHERE id = {$gTid}");

include_once('templatecode/header.php'); 
?>
<h3>刪除文章</h3>
<p>你決定要刪除<strong><?php echo $title; ?></strong>嗎?</p>
<a class="btn btn-primary" href="delete.php?did=<?php echo $gTid; ?>">確定</a> <a class="btn btn-default" href="modifyentryindex.php">返回</a>
<hr />
<?php include_once('templatecode/footer.php'); ?>