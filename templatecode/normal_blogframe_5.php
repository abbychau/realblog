<?php 
	if (!$htmltitle){$htmltitle = "Real Blog";}
	$noBS = 1;
?>
<!DOCTYPE html>
<html>
	<head>
		
		<? include(template("common_metas"));?>
		<link rel='stylesheet' href='https://cdn.jsdelivr.net/gh/kognise/water.css@latest/dist/dark.css'>

		<style type="text/css">
			.navbar-nav > li > a{padding:0 0.6em 0.6em 0.6em;}
			<?=$background;?>
			.searchbar{max-width:300px;float:right;}
			.pagination li{display:inline;}
		</style>
	</head>
	
	<body>
		
		<!-- system needs, do not remove start  -->
		<? include(template('system_boxes'));?>
		<!-- system needs, do not remove bottom -->
		
		
		<div style='position:fixed;right:0;top:0;z-index:12; width:100px'>
<script>
  (function() {
    var cx = '013715907533463635700:n6urnqda6ru';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:search></gcse:search>
			<a href='/compose.php' class='btn btn-default'>寫!</a>
			<a href='/modifyinfo.php' class='btn btn-default'>改!</a>
			
		</div>
		<div class="container">
			<div class="blogtitle page-header">
				<h1><a href="/<?=$blogInfo['username']; ?>"><?=$blogInfo['blogname']; ?></a>
					<small class='slogan'><?=$blogInfo['slogan'];?></small>	
				</h1>
			</div>
			<? if($getPages){?>
				<div class='nav'>
					<?php foreach($getPages as $v) { ?>
						<strong>置頂:</strong> <a class='<?=($gTid == $v['id'])?"active":"";?>' href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>"><?=$v['title']; ?> <span class='badge'><?=$v['comment_count']; ?></span></a>
					<?php } ?>
				</div>
			<?}?>
			<div class='nav'>
				<strong>分類:</strong>
				<?php foreach($getTypes as $v) { ?>
					<a href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>/0"><?=$v['name']; ?> x <?=$v['ce']; ?></a> | 
				<?php } ?>
			</div>
			
			
			<?=$blogInfo['topbar'];?>
			<div class='row'>				
				<div class="mainpart panel panel-default ">
					<div class="panel-body">
						<? include(template($mainTemplate)); ?>
					</div>
				</div>
				
				
			</div>
		</div>
		<div class="footer">
			<a class='rsslink' href="<?=$rsslink;?>"><span class='glyphicon .glyphicon-cloud'></span> RSS</a>
			<?=$blogfooter; ?>
            <br />
            META: <a href="/modifyinfo.php">設置</a>
			
		</div>
		
		
	</body>
</html>
