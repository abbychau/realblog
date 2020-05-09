<?php 
	if (!$htmltitle){$htmltitle = "Real Blog";}
?>
<!DOCTYPE html>
<html>
	<head>
		
		<? include(template("common_metas"));?>
		
		<?if($blogInfo['bootstrap_theme']){?><link rel="stylesheet" href="<?=$blogInfo['bootstrap_theme'];?>" /><? } ?>
		<style type="text/css">
			.container{width:100%;max-width: 1280px}
			.navbar-nav > li > a{padding:0 0.6em 0.6em 0.6em;}
			.nav_pages a{display: block;
			margin-left: 1em;
			float: left;
			border: 1px solid rgb(204, 204, 204);
			padding: 4px;
			border-radius: 2px;
			font-size: 1.2em;
			}
			.nav_pages a.active{background:#EEE;}
			<?=$background;?>
			
		</style>
		

	</head>
	
	<body>
		
		<!-- system needs, do not remove start  -->
		<? include(template('system_boxes'));?>
		<!-- system needs, do not remove bottom -->
		
		
		<div style='position:fixed;right:0;top:0;z-index:12'>
			<a href='/compose.php' class='btn btn-default'>寫!</a>
		</div>
		<div class="container">
			<div class="blogtitle page-header">
				<h1><a href="/<?=$blogInfo['username']; ?>"><?=$blogInfo['blogname']; ?></a>
					<small class='slogan'><?=$blogInfo['slogan'];?></small>	
				</h1>
			</div>
			
			<?=$blogInfo['topbar'];?>
			<div class='row'>
				<div class="col-xs-12 col-sm-3 col-lg-3">
					
					<nav class="navbar navbar-default" role="navigation">
						<div class="container-fluid">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
									<span class="sr-only">切換選單</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<span class="navbar-brand">分類選單</span>
							</div>
							
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul class="nav navbar-nav">
									<?php foreach($getTypes as $v) { ?>
										<li class='<?=($gType == $v['id'])?"active":"";?>'>
											<a href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>/0"><?=$v['name']; ?> <span class='badge'><?=$v['ce']; ?></span></a>
											
										</li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</nav><? include(template('sidebar')); ?>
				</div>
				
				<div class="col-xs-12 col-sm-9 col-lg-9">
					<div class="mainpart panel panel-default ">
                        <div class="panel-body">
						    <? include(template($mainTemplate)); ?>
						</div>
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