<!DOCTYPE html>
<html>
<head>
<? include(template("common_metas"));?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.css" />

<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />	

<link rel="stylesheet" type="text/css" href="/css/base.css" />
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" />
<link href="//netdna.bootstrapcdn.com/bootswatch/3.3.4/readable/bootstrap.min.css" rel="stylesheet">


<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.js"></script>
<script type="text/javascript" src="http://connect.facebook.net/zh_TW/all.js#appId=255045087298&amp;xfbml=1"></script>


<style type="text/css">
.btn{color:white;}
<?=$background;?>
</style>


<script type="text/javascript">
/* <![CDATA[ */

var current_url = 'http://<?=$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];?>';

var docTitle=document.title;

$.fx.speeds._default = 1000;

function dialog(url, title, iframe,width){
	var tempContent;
	if(iframe==true){
		tempContent = '<iframe style="border:none;height:600px;width:100%" src="'+url+'"></iframe>';
		$('#modelDialog').find(".modal-body").html(tempContent);
	}else{
		$('#modelDialog').find(".modal-body").load(url);
	}
	$('#modelDialog').modal('show');
	$('#modelDialog').find(".modal-title").html(title);
	if(width>1){
	//$('#modelDialog').css("width", width );
	}
}



function checkNoti(){
$.get('/ajaxdata.php',{type: 'notify'},
  function(data){
	$('#notify').html(data);
	if(data != '0'){
		document.title = '(' + data + ')'+docTitle;
	}else{
		document.title = docTitle;
	}
  });
}


<?if($isLog){?>
setInterval("checkNoti()",1000*60);
checkNoti();
<?}?>
$(document).ready(
	function(){
		prettyPrint();
	}
);
/* ]]> */
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4293967-12']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>	
</head>

<body>
  <div id="modelDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body"></div>
		<!--
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
		
		-->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


<div class="ajaxbox ui-widget-content" id="showajax2" style="border-radius:2px;z-index:2;position:fixed; display:none"></div>
<div id="fb-root"></div>

<div class="navbar navbar-default navbar-fixed-top" id='rb_navbar'>
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">RB</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<?php if($isLog){?>
					<li><a href="http://members.zkiz.com/notifications.php" target='blank' class='withSignal'>N(<span id="notify"><?=$my['notification'];?></span>)</a></li>
				<?}?>

				<?if($isLog){?>
				<li><a href="/compose.php">寫文</a></li>
					<li><a href="/modifyentryindex.php">改文</a></li>
				
				<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$gUsername;?><b class="caret"></b></a>
					<ul class="dropdown-menu">
				<li><a href="/<?=$gUsername;?>">看自己的Blog</a></li>
				<li><a href="/modifyinfo.php">設置</a></li>
				<li><a class='withNote' href='http://realforum.zkiz.com/logout.php'>登出</a></li>
				</ul>
				</li>
				<?}?>
			</ul>
			<?if($isLog){?>
				<form name="searchform" id="midBarForm" method="get" action="http://www.zkiz.com/tag.php" class="navbar-form navbar-right" action="http://zkiz.com/tag.php" >
					<div class="form-group">
						<input type="text" class="form-control" id="midBarField" name="tag" placeholder="ZKIZ.com 搜尋" value="<?=$_GET['tag'];?>" />
					</div>
					<button type="submit" class="btn btn-success">Search</button>
				</form>

				<?}else{?>
				<form class="navbar-form navbar-right" name="form1" method="post" action="http://members.zkiz.com/processlogin.php">
					<div class="form-group">
						<input name="username" type="text" placeholder="使用者名稱" class="form-control">
					</div>
					<div class="form-group">
						<input name="password" type="password" placeholder="密碼" class="form-control">
					</div>
					<input type="hidden" name="refer" value="<?=curURL();?>" />
					<button type="submit" class="btn btn-success">登入</button>
				</form>
				
			<?}?>
		</div><!--/.navbar-collapse -->
	</div>
</div>

<div class="container">
<div class="ui-widget-content innerwrapper grayshadow <?=$noSidebar?"col-xs-12 col-sm-12 col-lg-12":"col-xs-12 col-sm-9 col-lg-9";?>">