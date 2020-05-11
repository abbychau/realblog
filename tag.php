<?php 
//!!!!!!!!!!!!

//!!!!!!!!!!
//!!!!!!!!!!
//!!!!!!!!!!
//!!!!!!!!!!
//!!!!!!!!!!
//!!!!!!!!!!
//!!!!!!!!!!
//!!!!!!!!!!
//!!!!!!!!!!
//!!!!!!!!!!
	exit;
require_once('include/common.php'); 
//params
$gType = safe($_GET['type']);
$gPageNo = safe($_GET['pageNum_viewconlist']);
if (isset($_GET['username'])) {
	$gUser = safe($_GET['username']);
	$gZid = dbRs("SELECT id FROM zb_user WHERE username = '{$gUser}'");
}else{
	$gZid = safe($_GET['zid']);
}

//get Settings
$row_getSettings = dbRow("SELECT * FROM zb_user WHERE id = {$gZid}");
$maxRows_viewconlist = $row_getSettings['displaynum'];
$curPageNo = ($gPageNo == "") ? 0 : $gPageNo;
$startRow_viewconlist = $curPageNo * $maxRows_viewconlist;
$background = $row_getSettings['background'];
$blogfooter = $row_getSettings['footer'];
$blogname = $row_getSettings['blogname'];
$slogan = $row_getSettings['slogan'];
$sidebar = $row_getSettings['sidebar'];
$username = $row_getSettings['username'];
$htmltitle = "Real Blog - {$blogname}";
$keywords = $blogname;
$description = "{$blogname}, {$gUser}, ".htmlspecialchars($slogan);
$tag=safe($_GET['tag']);

$conlistIds= getEntryByTag($tag, 2);
$conlistIds=implode(",",$conlistIds);

$viewconlist = dbAr("SELECT * FROM zb_contentpages WHERE ownerid = {$gZid} AND isshow=1 AND tag={$tag} AND id IN({$conlistIds}) ORDER BY id DESC LIMIT {$startRow_viewconlist}, {$maxRows_viewconlist}");


//get total rows
$totalRows_viewconlist = dbRs("SELECT count(*) as ce FROM zb_contentpages WHERE ownerid = {$gZid} AND isshow=1 {$extCons}");
$totalPages_viewconlist = ceil($totalRows_viewconlist/$maxRows_viewconlist)-1; //and totalpage too ^^

//getType - Get the top list of types with numbers
$types = dbAr("SELECT count(*) as ce, b.id, b.ownerid, b.name FROM zb_contentpages a, zb_contenttype b WHERE b.ownerid = :zid AND a.type = b.id group by b.id",['zid'=>$gZid]);


//get comments
$comments = dbAr("SELECT a.pageid as pid, b.id, SUBSTR(a.content,1,30) as conbar FROM zb_comment a, zb_contentpages b where a.pageid = b.id and b.ownerid = {$gZid} ORDER BY a.time DESC LIMIT 15");

$sidebar2.="<div class='newreply' style='overflow-x:hidden'><h5>最新回覆</h5><ul>";
foreach ($comments as $row_getComment) {
$sidebar2.="<li><a href='http://realblog.zkiz.com/{$username}/".$row_getComment['pid']."'>".htmlspecialchars($row_getComment['conbar'])."...</a><br /></li>";
}
$sidebar2.="</ul></div>";

$rsslink = "http://realblog.zkiz.com/rssdata/{$gZid}.xml";

include_once('include/parsehtml.php'); 
include_once('templatecode/header.php'); 
?>
<?php if($blogname != ""){ ?>
	<h3 class="blogtitle"><a href="/<?=$username; ?>" style="text-decoration:none"><?=$blogname; ?></a></h3>
	<?php if ($slogan!=""){ ?>
		<div class="slogan"><?=$slogan;?></div>
	<?php }?>
	<?php if ($row_getSettings['topbar']!=""){ ?>
		<?=$row_getSettings['topbar'];?><br />
	<?php } ?>
<?php }else{ ?>
	查無此BLOG<br />
<?php } ?>

<div class="mainpart">
<?php if($totalRows_viewconlist != 0){ ?>
	<div class="cate">
	
	<?php foreach($types as $row_gettype) { ?>
	<span class="cateitem <?=($gType == $row_gettype['id'])?"selected":"";?>">
	<a href="/<?=$username; ?>/<?=$row_gettype['id']; ?>/0"><?=$row_gettype['name']; ?></a>(<?=$row_gettype['ce']; ?>)
	</span>
	<?php } ?>
	
	</div>
	<div style="clear:both"></div>
	<br />
	<a style="text-decoration:none" href="<?=$rsslink;?>"><img src="/images/rss.png" alt="RSS" /> 按此訂閱RSS</a><br />
	<br />
	<?php $i = 1; ?>
	<?php  foreach ($viewconlist as $row_viewconlist) { ?>
	<h4 class="articletitle"><a href="/<?=$username; ?>/<?=$row_viewconlist['id']; ?>"><?=$row_viewconlist['title']; ?></a>
		<?php if($gUsername==$username){?>
			<span style="font-size:12px; font-weight:normal">
			<a href="/modifyentry.php?tid=<?=$row_viewconlist['id']; ?>">修改</a>&nbsp;
			<a href="/delete.php?tid=<?=$row_viewconlist['id']; ?>">刪除</a>
			</span>
		<?php } ?>
	</h4>
	<div style="margin:2px;"><strong>發表時間：</strong><?=$row_viewconlist['datetime']; ?></div>
	
	<?php 
	if($row_viewconlist['displaymode'] == 0){
		if($row_getSettings['displaytype']==1){
			$showcontent=true;
		}else{
			$showcontent=false;
		}
	}else{
		if($row_viewconlist['displaymode'] == 1){
			$showcontent=false;
		}else{
			$showcontent=true;
		}
	}?>
	<?php if($showcontent){?>
		<div class="contentbody">
		<?php
			echo preg_replace('/\[\[(.*?)\]\]/is', '<a href="http://realforum.zkiz.com/thread.php?wikiterm=$1">$1</a>', $row_viewconlist['content']);
		?>
		</div>
	<? } ?>
<div class="posted">
	<a style="cursor:pointer" onclick="$('#showcomment<?=++$i;?>').toggle('slow').load('/showcomment.php?tid=<?=$row_viewconlist['id']; ?>');">看評論 (<?=$row_viewconlist['commentnum']; ?>)</a>
	<a href="/<?=$username; ?>/<?=$row_viewconlist['id']; ?>">看全文</a>
	<div id="showcomment<?=$i;?>" style="display:none"><img src="/images/loading.gif" alt="loading..." />&nbsp;</div>
</div> 
	<hr />
	<?php } ?><br />

	<br />
	<?php if ($curPageNo > 0) { ?>
	<a href="/<?=$username."/".$temptype; ?>/0">第一頁</a>
	<a href="/<?=$username."/".$temptype."/".($curPageNo - 1); ?>">上一頁</a>
	<?php } ?>
	<?php 
	if($totalPages_viewconlist!=0){
	for($i=0;$i <= $totalPages_viewconlist; $i++){
			if($i==$curPageNo){echo "$i ";
			}else{echo "<a href='/".$username."/".$temptype."/$i'>$i</a> ";}
		}
	} ?>
	<?php if ($curPageNo < $totalPages_viewconlist) { ?>
	<a href="/<?=$username."/".$temptype."/".($curPageNo + 1); ?>">下一頁</a>
	<a href="/<?=$username."/".$temptype."/".$totalPages_viewconlist; ?>">最後一頁</a>
	<?php } ?>
	<br />
<?php }else{ ?>
<span class="noarticle">沒有文章.</span>
<?php }?>
</div>
<?php 
include_once('templatecode/footer.php'); 
?>