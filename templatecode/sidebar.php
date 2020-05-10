<? if($_GET['username']){?>
<script>
	function search_google_from_sidebar(){
		window.open("http://www.google.com/search?q="+encodeURIComponent($("#google_search_field").val()+" site:realblog.zkiz.com/<?=$blogInfo['username']; ?>"), '_blank');
	}
	</script>
<!-- Blog Search Well -->
<div class="well">
	<h4>Blog 搜尋</h4>
	<div class="input-group">
		<input type="text" class="form-control" id='google_search_field' onkeypress="if (event.keyCode==13){ search_google_from_sidebar() ;return false;}" />
		<span class="input-group-btn">
			<button class="btn btn-default" type="button" onclick='search_google_from_sidebar()'>
				<span class="glyphicon glyphicon-search"></span>
		</button>
		</span>
	</div>
	<!-- /.input-group -->
</div>

<!-- Blog Categories Well -->
<div class="well">
	<h4>Blog Categories</h4>
	<?
	if($getTypes){
		$getTypesPieces = array_chunk($getTypes, ceil(count($getTypes) / 2));
	}
	?>

	
	<div class="row">
	<? if($getTypesPieces[0]){?>
		<div class="col-lg-6">
			<ul class="list-unstyled">
		<?php foreach($getTypesPieces[0] as $v) { ?>
			<li class="cateitem <?=($gType == $v['id'])?"selected":"";?>">
				<a href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>/0"><?=$v['name']; ?></a>(<?=$v['ce']; ?>)
			</li>
		<?php } ?>
			</ul>
		</div>
	<?}?>
<? if($getTypesPieces[1]){?>
		<div class="col-lg-6">
			<ul class="list-unstyled">
		<?php foreach($getTypesPieces[1] as $v) { ?>
			<li class="cateitem <?=($gType == $v['id'])?"selected":"";?>">
				<a href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>/0"><?=$v['name']; ?></a>(<?=$v['ce']; ?>)
			</li>
		<?php } ?>
			</ul>
		</div>
<?}?>
		<!-- /.col-lg-6 -->
	</div>
	<!-- /.row -->
</div>
<?}?>
<?php if ($sidebar!=""){?>
	<div class="cussidebar">
		<?=$sidebar; ?>
	</div>
<? } ?>
<?if($blogNewArticle){?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">最新文章</h4>
		</div>
        
		<ul class="list-group">
            <?foreach($blogNewArticle as $v){?>
                <li class="list-group-item"><?=$v;?></li>
            <?}?>
        </ul>
	</div>
<?}?>
<?if($blogNewReply){?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">最新回覆</h4>
		</div>
		<ul class="list-group">
			<?foreach($blogNewReply as $v){?>
				<li class="list-group-item"><?=$v;?></li>
			<?}?>
		</ul>
	</div>
<?}?>
<?if($hotReply){?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">熱門博文</h4>
		</div>
		<ul class="list-group">
			<?foreach($hotReply as $v){?>
				<li class="list-group-item"><?=$v;?></li>
			<?}?>
		</ul>
	</div>
<?}?>
<?if ($sidebar2!=""){?>
	<div class="syssidebar">
		<?=$sidebar2; ?>
	</div>
<? } ?>

<?php if(isset($rsslink)){?>
<br />
<a class='rsslink' href="<?=$rsslink;?>"><span class='glyphicon .glyphicon-cloud'></span> 按此訂閱RSS</a>
<?}?>

<?if(false && !$noAds){?>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- Ad Unit #zkiz_tag_left_ATF_160x600 -->
	<ins class="adsbygoogle"
	style="display:inline-block;width:160px;height:600px"
	data-ad-client="ca-pub-4549800596928715"
	data-ad-slot="6833483781"></ins>
	<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
	</script>
<?}?>	