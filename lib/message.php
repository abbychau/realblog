<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="zkiz.com Message" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>ZKIZ.com - Message</title>
		<link rel="shortcut icon" href="/favicon.ico" />
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script>
			var tmp = false;
		</script>
		<style>
			.button{background:#DDD;border:#CCC 2px solid;padding:5px 10px;font-size:12px;}
		</style>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-4293967-12', 'zkiz.com');
ga('require', 'displayfeatures');
ga('send', 'pageview');
</script>
	</head>
	
	<body>
		<div class="reply" style="margin:20px auto; border:1px solid #CCC; padding:20px; width:80%;">
			<h3><?=$ttl;?></h3>
			<div style="font-size:14px; font-weight:bold" id='msg'>
				<div>
					<?=$msg;?>
				</div>

				<?php if($defaulturl!=""){?>
					<div id='default' style="margin:30px 0 10px 0">
						建議回到: <a href='<?=$defaulturl;?>'><?=$defaulturl;?></a>。
					</div>
				<?php }?>
				<div style="margin:30px 0 10px 0">
					<a class='button' style="font-size:12px" onclick='history.go(-1);'>返回</a>
				</div>
			</div>
		</div>
		
		
	</body>
	
</html>