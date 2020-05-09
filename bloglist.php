<?php require_once('Connections/zkizblog.php'); 
	$currentPage = $_SERVER["PHP_SELF"];
	
	$maxRows_getBloglist = 100;
	$pageNum_getBloglist = 0;
	if (isset($_GET['pageNum_getBloglist'])) {$pageNum_getBloglist = $_GET['pageNum_getBloglist'];}
	$startRow_getBloglist = $pageNum_getBloglist * $maxRows_getBloglist;
	$query_getBloglist = "
	SELECT username, blogname, title, b.id, datetime FROM
	(SELECT id, username, blogname,blacklisted FROM zb_user) a,
	(SELECT b1.id , b1.ownerid, title, datetime FROM zb_contentpages b1, (SELECT ownerid, max(id) as id FROM zb_contentpages group by ownerid) b2 WHERE b1.id = b2.id AND b1.ownerid = b2.ownerid) b
	WHERE a.id = b.ownerid
	AND a.blacklisted <> 1
	ORDER BY b.id DESC
	";
	$query_limit_getBloglist = sprintf("%s LIMIT %d, %d", $query_getBloglist, $startRow_getBloglist, $maxRows_getBloglist);
	$getBloglist = dbAr($query_limit_getBloglist);
	
	if (isset($_GET['totalRows_getBloglist'])) {
		$totalRows_getBloglist = intval($_GET['totalRows_getBloglist']);
	} else {
		$totalRows_getBloglist = count($getBloglist);
	}
	$totalPages_getBloglist = ceil($totalRows_getBloglist/$maxRows_getBloglist)-1;
	
	$queryString_getBloglist = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (stristr($param, "pageNum_getBloglist") == false && 
			stristr($param, "totalRows_getBloglist") == false) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_getBloglist = "&" . htmlentities(implode("&", $newParams));
		}
	}
	$queryString_getBloglist = sprintf("&totalRows_getBloglist=%d%s", $totalRows_getBloglist, $queryString_getBloglist);
	
	include_once('templatecode/header.php'); 
?>
<style type='text/css'>
.ui-accordion .ui-accordion-content {padding:1em}
.sidebar{display:none;} .content{margin:0px;} .indexBlock{width:970px;} 
</style>
<h3>Real Blog - BLOG 列表</h3>
<table>
	<tr style="font-weight:bold;">
		<td width="200" >Blog名稱</td><td>Blog主</td><td>最後主題</td><td width="120">發表於</td>
	</tr>
	<tr>
	<td style="border-bottom:1px #DDD solid" colspan="4">
	
	</td>
	<?php foreach($getBloglist as $row_getBloglist){ ?>
		<tr>
			<td><a href="/<?php echo $row_getBloglist['username']; ?>"><?=mb_substr($row_getBloglist['blogname'],0,40,"utf8"); ?></a></td>
			<td><?php echo $row_getBloglist['username']; ?></td>
			<td><a href="/<?php echo $row_getBloglist['username']; ?>/<?=$row_getBloglist['id'];?>"><?=mb_substr($row_getBloglist['title'],0,40,"utf8");?></a></td>
			<td><?=timeago(strtotime($row_getBloglist['datetime']));?></td>
		</tr>
	<?php } ?>
</table>
<p>
	共<?php echo $totalRows_getBloglist ?> 個Blog <br />
	正在顯示第<?php echo ($startRow_getBloglist + 1) ?> 至 <?php echo min($startRow_getBloglist + $maxRows_getBloglist, $totalRows_getBloglist) ?> 個
	<br />
	<?php if ($pageNum_getBloglist > 0) { ?>
		<a href="<?php printf("%s?pageNum_getBloglist=%d%s", $currentPage, 0, $queryString_getBloglist); ?>">第一頁</a>
		<a href="<?php printf("%s?pageNum_getBloglist=%d%s", $currentPage, max(0, $pageNum_getBloglist - 1), $queryString_getBloglist); ?>">上一頁</a>
	<?php } ?>
	
	<?php if ($pageNum_getBloglist < $totalPages_getBloglist) { ?>
		<a href="<?php printf("%s?pageNum_getBloglist=%d%s", $currentPage, min($totalPages_getBloglist, $pageNum_getBloglist + 1), $queryString_getBloglist); ?>">下一頁</a>
		<a href="<?php printf("%s?pageNum_getBloglist=%d%s", $currentPage, $totalPages_getBloglist, $queryString_getBloglist); ?>">最後一頁</a>
	<?php } ?>
</p>
<?php include_once('templatecode/footer.php'); ?>