<?php 
	if (!$htmltitle){$htmltitle = "Real Blog";}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		
		<? include(template("common_metas"));?>
		
		<link href="/template_dependencies/4/css/styles.css" rel="stylesheet">
		
		<style>
			.artNav,.content_container{padding-left:15px;padding-right:15px;}
			
			.viewpage_tag{margin:1em}
			.label-default{margin:.5em;}
			.box{background-color:black;
				background-image:
				radial-gradient(white, rgba(255,255,255,.2) 2px, transparent 40px),
				radial-gradient(white, rgba(255,255,255,.15) 1px, transparent 30px),
				radial-gradient(white, rgba(255,255,255,.1) 2px, transparent 40px),
				radial-gradient(rgba(255,255,255,.4), rgba(255,255,255,.1) 2px, transparent 30px);
				background-size: 550px 550px, 350px 350px, 250px 250px, 150px 150px; 
				background-position: 0 0, 40px 60px, 130px 270px, 70px 100px;}
							
			.contentbody{padding:0.5em}
			<?=$background;?>
		</style>
	</head>
	<body>
		<div class="wrapper">
			<?=$blogInfo['topbar'];?>
			<div class="box">
				<div class="row">
					<!-- sidebar -->
					<div class="column col-sm-3" id="sidebar">
						<a class="logo" href="/<?=$blogInfo['username']; ?>"><?=mb_substr($blogInfo['blogname'],0,1,"utf8"); ?></a>
						<small class='slogan'><?=$blogInfo['slogan'];?></small>	
						
						<ul class="nav">
							<li>
								<form method="get" action="/searchresults.php" onsubmit="if(this.searchtext.value.length<2){alert('搜索內容過短');return false;}">
									
									
									<input name="ownerid" value="4" type="hidden">
									<input type="text" style="border:none;background:none" maxlength="20" name="searchtext" placeholder="搜尋...">
									<span class="input-group-btn">
										<input style='background:0;border:none' name="Submit" id="sb_submit" value="搜尋" type="submit" />
									</span>
									
								</form>
							</li>
							
							<!-- content -->
							<? if($getPages){?>
								
								<?php foreach($getPages as $v) { ?>
									
									<li>
										<a class='<?=($gTid == $v['id'])?"active":"";?>' href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>"><?=$v['title']; ?></a>
									</li>
								<?php } ?>
							<?}?>
							<? if($isLog){?>
								<li><a href='/compose.php'>寫!</a></li>
							<?}?>
						</ul>
						
						
						
						
						<ul class="nav hidden-xs" id="sidebar-footer">
							<li>
								<a href="/<?=$blogInfo['username']; ?>"><h3><?=$blogInfo['blogname']; ?></h3>Powered by RealBlog</a>
							</li>
						</ul>
					</div>
					<!-- /sidebar -->
					
					<!-- main -->
					<div class="column col-sm-9" id="main">
						<div class="padding">
							<div class="full col-sm-9">
								
								<!-- content -->
								<? if($getPages){?>
                                    <div class="well">
									<div class="row">
										<div class="col-md-10 col-xs-12">
											<div class="row"> 
												
												<?php foreach($getTypes as $v) { ?>
													
													<div class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
														<a href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>/0"><?=$v['name']; ?> <span class='badge'><?=$v['ce']; ?></span></a>
													</div>
												<?php } ?>
											</div>
										</div>
										<div class="col-md-2 hidden-xs">
											<a href="/<?=$blogInfo['username'];?>" class="pull-right"><img src="<?=dbRs("SELECT pic FROM zf_user WHERE username='{$blogInfo['username']}'");?>" class="img-circle"></a>
										</div>
									</div>
                                    </div>
								<?}?>

								
								<!--/stories-->
								<? include(template($mainTemplate)); ?>
								<hr />
								<?if($blogNewArticle){?>
									<div class='col-xs-12 col-sm-6'>
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
									</div>
								<?}?>
								<?if($blogNewReply && $blogInfo['comment_system1'] == 1){?>
									<div class='col-xs-12 col-sm-6'>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">最新評論</h4>
											</div>
											<ul class="list-group">
												<?foreach($blogNewReply as $v){?>
													<li class="list-group-item"><?=$v;?></li>
												<?}?>
											</ul>
										</div>
									</div>
								<?}?>
								<?if($blogInfo['comment_system3'] == 1 && $blogInfo['disqus_name']!=""){?>
<div id="recentcomments" class="dsq-widget">
<h2 class="dsq-widget-title">最新評論</h2>
</div>
<script type="text/javascript" src="http://<?=$blogInfo['disqus_name'];?>.disqus.com/recent_comments_widget.js?num_items=5&hide_avatars=0&avatar_size=32&excerpt_length=200&hide_mods=0"></script>
								<?}?>
								<?if($hotPost){?>
									<div class='col-xs-12 col-sm-6'>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">熱門博文</h4>
											</div>
											<ul class="list-group">
												<?foreach($hotPost as $v){?>
													<li class="list-group-item"><?=$v;?></li>
												<?}?>
											</ul>
										</div>
									</div>
								<?}?>
							</div>
							<div class="row" id="footer">    
								<div class="col-sm-6">
									<a class='rsslink' href="<?=$rsslink;?>"><span class='glyphicon .glyphicon-cloud'></span>RSS</a> | 
									<a class='rsslink' href="/"><span class='glyphicon .glyphicon-cloud'></span>RealBlog</a>
									<br />
									META: <a href="/modifyinfo.php">設置</a>
								</div>
								<div class="col-sm-6">
									<p>
										<a href="//abby.zkiz.com" class="pull-right">Copyright @ abbychau</a>
									</p>
									<br />
									<div class="pull-right fb-like" data-href="http://realblog.zkiz.com/<?=$blogInfo['username']; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
								</div>
								<div class="col-md-12">
									<?=$blogfooter; ?>
								</div>
							</div>
							
							<div class="col-sm-12">
								<div class="page-header text-muted divider">
									Connect with Me
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-6">
									<a href="http://twitter.com/abbychau">Twitter</a> <small class="text-muted">|</small> <a href="//facebook.com/zkizabby">Facebook</a> <small class="text-muted">|</small> <a href="https://plus.google.com/u/1/+AbbyChau">Google+</a>
								</div>
							</div>
						</div><!-- /padding -->
					</div>
					<!-- /main -->
				</div>
			</div>
		</div>
		<!-- system needs, do not remove start  -->
		<? include(template('system_boxes'));?>
		<!-- system needs, do not remove bottom -->
		
		
		<style type="text/css">
			<?=$background;?>
		</style>
		

	</body>
</html>