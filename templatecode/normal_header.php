<?php 
	if (!$htmltitle){$htmltitle = "Real Blog";}
?>
<!DOCTYPE html>
<html lang="zh-tw">
	<head>
		<? include(template("common_metas"));?>
		
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" />

		<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.6.16/summernote.min.css" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.6.16/summernote.min.js"></script>
		
		<script src="/js/summernote-ext-video.js"></script>
		<script src="/js/summernote-zh-TW.js"></script> -->
		<link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
		<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({
                google_ad_client: "ca-pub-4549800596928715",
                enable_page_level_ads: true
            });
        </script>



		<style type="text/css">
			.abstract{color:#777}
*{word-break:break-all;}
			<?php if ($background==""){ ?>
				.wrapper{width:980px; margin-left:auto; margin-right:auto;}
			<?php }else{ echo $background;}?>
		</style>
	</head>
	
	<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_HK/sdk.js#xfbml=1&version=v2.5&appId=255045087298";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	
	
		<div id="modelDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body"></div>
				</div>
			</div>
		</div>
		
		<div class="navbar navbar-default" id='rb_navbar'>
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#top_nav_main" style='border:0'>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" style="padding-left:1.5em" href="/<?=$blogInfo['username']; ?>"><?=isset($gNavTitle)?$gNavTitle:"RealBlog";?></a>
				</div>
				<div class="navbar-collapse collapse" id="top_nav_main">
					<ul class="nav navbar-nav">
						<?php if($isLog){?>
							<li>
                                <a href="//members.zkiz.com/notifications.php" target='blank' class='withSignal'>
                                    <span class='glyphicon glyphicon-bell'></span>
							        <span id="notify" class='badge'><?=$my['notification'];?></span></a>
                            </li>
						<?}?>
						

						<?if($isLog){?>
							<li><a href="/compose.php"><span class='glyphicon glyphicon-pencil'></span> 寫文</a></li>
						<?}?>
					</ul>
					<?if($isLog){?>
						<ul class='nav navbar-nav navbar-right'>
							
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<?if($my['pic']){?><img src='<?=str_replace("http://","https://",$my['pic']);?>' width='16' /> <?}?><?=$my['username'];?><b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="/<?=$gUsername;?>">看自己的Blog</a></li>
									<li><a href="/modifyinfo.php">設置</a></li>
									<li><a class='withNote' href='//realforum.zkiz.com/logout.php'>登出</a></li>
								</ul>
							</li>
						</ul>
					<?}?>
					<?if($isLog){?>
<script>


$("#navbar_search").keypress(function(event) {
if (event.which == 13) {
event.preventDefault();
$("navbar_form").submit();
}
});


</script>
						<form name="searchform" id="navbar_form" method="get" action="//www.zkiz.com/tag.php" class="navbar-form navbar-right" action="//zkiz.com/tag.php" >
							<div class="form-group">
								<input style="width:120px" type="text" class="form-control" id="midBarField" name="tag" placeholder="ZKIZ.com 搜尋" value="<?=$_GET['tag'];?>" />
							</div>
						</form>
						
						<?}else{?>

                        <ul class="nav navbar-nav navbar-right">
                            <li><a onclick="$('#login_form_nav').submit()">登入</a></li>
                            <li><a href="//members.zkiz.com/reg.php">註冊</a></li>
                        </ul>
						<form class="navbar-form navbar-right" id="login_form_nav" method="post" action="//members.zkiz.com/processlogin.php">
							<div class="form-group">
								<input name="username" type="text" placeholder="使用者名稱" class="form-control" />
							</div>
							<div class="form-group">
								<input name="password" type="password" placeholder="密碼" class="form-control" />
							</div>
							<input type="hidden" name="refer" value="<?=curURL();?>" />

						</form>

						<?}?>
				</div><!--/.navbar-collapse -->
			</div>
		</div>
		
		<div class="container">
		<div class="<?=$noSidebar?"col-xs-12 col-sm-12 col-lg-12":"col-xs-12 col-sm-8 col-lg-8";?>">		