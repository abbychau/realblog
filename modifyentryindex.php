<?php 
	require_once('include/common.php'); 
	if(!$isLog){die ("Please Login");}
	if(!$gId){
		header("location: activate.php");
	}
	$gDid = intval($_GET['did']);
	if($gDid != ""){
		if(dbRs("SELECT ownerid FROM zb_contentpages WHERE id = {$gDid}") != $gId){screenMessage("Error","Access Denied!");}
		dbQuery("DELETE FROM zb_contentpages WHERE id={$gDid}");
		cacheVoid($rsskey.$gId);
		//screenMessage("刪除完成","<a href='/$gUsername'>返回自己的Blog</a><br /><a href='modifyentryindex.php'>返回修改文章列表</a>");
		header("location: /modifyentryindex.php");
		exit;
	}
// 	$fb = getFacebook();
	$maxRows_viewconlist = 40;
	$page = isset($_GET['page'])?intval($_GET['page']):0;
	
	$startRow_viewconlist = $page * $maxRows_viewconlist;
	if(isset($_GET['modisearch'])){
		//TODO: unsafe
		$searchtxt = safe($_GET['modisearch']);
		$extcon = " AND title LIKE '%$searchtxt%' ";
	}
	if($_GET['search'] == "hidden"){
		$extcon .= " AND isshow = 0 ";
	}	
	
	$viewconlist = dbAr("SELECT a.title as title, a.datetime as datetime, a.id as id, b.name as type, b.id as type_id
	FROM `zb_contentpages` a, zb_contenttype b 
	WHERE a.type = b.id AND a.ownerid = $gId $extcon ORDER BY id DESC LIMIT $startRow_viewconlist, $maxRows_viewconlist");
	
	if (isset($_GET['totalRows_viewconlist'])) {
		$totalRows_viewconlist = $_GET['totalRows_viewconlist'];
		} else {
		$totalRows_viewconlist = dbRs("SELECT count(1) FROM `zb_contentpages` WHERE ownerid = $gId $extcon");
	}
	$totalPages_viewconlist = ceil($totalRows_viewconlist/$maxRows_viewconlist)-1;
	
	
	
include_once('templatecode/header.php'); ?>
<h1>修改文章 - 列表</h1>

<div class='left'>
	<form method="get" action="<?=$_SERVER['PHP_SELF'];?>">
		<input type="text" name="modisearch" id="modisearch" value="<?=htmlspecialchars($_GET['modisearch']);?>" style="width:200px" />
		<input type="submit" value="搜索" />
	</form>
</div>
<div class='right'>
	<a class='btn btn-default btn-sm pull-right' href='<?=$_SERVER['PHP_SELF'];?>?search=hidden'>搜尋未發佈的文章</a>
</div>
<div class='clear'></div>
<?php if($viewconlist){?>
	<table class='table table-default'>
		<thead>
			<th width="100">類別</th>
			<th>標題</th>
			<th width="100">時間</th>
			<th width="50">動作</th>
		</thead>
		<?php foreach($viewconlist as $row_viewconlist) { ?>
			<tr>
				<td><a href='/<?=$gUsername;?>/<?php echo $row_viewconlist['type_id']; ?>/0'><?php echo $row_viewconlist['type']; ?></a></td>
				<td><a href='/<?=$gUsername;?>/<?php echo $row_viewconlist['id']; ?>'><?=$row_viewconlist['title']; ?></a></td>
				<td style="font-size:9pt"><?=$row_viewconlist['datetime']; ?></td>
				
				<td>
					<a href="compose.php?tid=<?php echo $row_viewconlist['id']; ?>" target='_blank'><span class="glyphicon glyphicon-pencil"></span></a>
					<a class='delete_confirm' data-blogtitle="<?=$row_viewconlist['title']; ?>" href="modifyentryindex.php?did=<?php echo $row_viewconlist['id']; ?>"><span class="glyphicon glyphicon-remove"></span></a>
				</td>
			</tr>
			
		<?php } ?>
	</table>
	<?}else{?>
	沒有文章
<?}?>
<p>
	<? generatePagin($page, "modifyentryindex.php", "page", $totalPages_viewconlist); ?>
</p>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<script>
	$(".delete_confirm").on("click", function(e) {
		var link = this;
		
		e.preventDefault();
		
		bootbox.dialog({
			title: "確定刪除",
			message: "<div>你確定要刪除["+$(this).attr('data-blogtitle')+"]嗎?<br />(這個操作不能復原)</div>",
			buttons: {
				main: {
				  label: "確定",
				  className: "btn-primary",
				  callback: function() {
					window.location = link.href;
				  }
				}
			}
		});
	});
</script>
<?php 
	include_once('templatecode/footer.php'); 
?>