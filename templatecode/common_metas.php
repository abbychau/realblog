<?php if(isset($rsslink)){?><link href="<?=$rsslink;?>" title="RSS 2.0" type="application/rss+xml" rel="alternate" /><?php } ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="<?=empty($description)?"zkiz.com提供, RealBlog, 博客, 部落格, 可以自行創建, 可以自行放置Adsense或其他廣告":$description; ?>" />
<meta name="keywords" content="<?=$keywords; ?>, RealBlog, 博客, 部落格" />
<meta property="og:title" content="<?=$htmltitle;?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?=isset($ogURL)?$ogURL:curURL();?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title><?=$htmltitle; ?></title>

<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
<? if(!$noBS){?>

<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />


<?}?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script async src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.0/jquery.waypoints.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css" type="text/css" media="all" /> -->

<!--AABP-->
<script type="text/javascript">var adblock = true;</script>
<script type="text/javascript" src="//realforum.zkiz.com/js/adframe.js"></script>
<script type="text/javascript">if(adblock){alert("Please do not enable adblock on our site. We rely on them.");}</script>
<!--END_AABP-->





<? if(!$gNoMathjax){ ?>
<script async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>

<script type="text/x-mathjax-config">MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});</script>
<? } ?>

<!--[if lt IE 9]>
	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<script type="text/javascript">
	/* <![CDATA[ */		
	var current_url = 'http://<?=$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];?>';
	var docTitle=document.title;
	
	function dialog(url, title, iframe){
		var tempContent;
        if(typeof url == 'string') {
            if (iframe == true) {
                tempContent = '<iframe style="border:none;height:600px;width:100%" src="' + url + '"></iframe>';
                $('#modelDialog').find(".modal-body").html(tempContent);
            } else {
                $('#modelDialog').find(".modal-body").load(url);
            }
        }else{
            //is a dom(should be)
            $('#modelDialog').find(".modal-body").html(url.html());
        }
		$('#modelDialog').modal('show');
		$('#modelDialog').find(".modal-title").html(title);
	}
	
	function checkNoti(){
		$.get('/ajaxdata.php',{type: 'notify'},
		function(data){
			$('#notify').html(data);
			if(data != '0'){
				document.title = docTitle + '(' + data + ')';
				}else{
				document.title = docTitle;
			}
		});
	}
	<?if($isLog){?>
		setInterval("checkNoti()",1000*60);
		checkNoti();
	<?}?>
	/* ]]> */
</script>
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	ga('create', 'UA-4293967-12', 'zkiz.com');
	ga('require', 'displayfeatures');
	ga('send', 'pageview');
</script>
<style>
	body {
	padding-bottom: 20px;
	}

    .list-group .list-group-item {
        border: 1px solid #eee;
        padding: .5em 1em;
    }

	a{cursor:pointer;}
	.viewpage_tag{margin:1em 0;}
	.viewpage_tag .label{font-size:1em}
	.left{float:left}
	.right{float:right}
	.clear{clear:both}
	.slogan{margin-top:-5px; padding-bottom:5px;}
	.center{margin-left:auto; margin-right:auto;}
	.myhide{display:none}
	.contentbody{
		margin-top:20px;
		/*margin-left:15px;*/
		font-size: 16px;
		line-height: 30px;
		padding:10px 0;
		word-wrap :break-word;
	}
	
	.cateitem{padding-left:5px}
	.selected{font-weight:bold;}
	.commentdiv{margin-bottom:.2em}
	.contentbody img{margin-top:3px; margin-bottom:3px;max-width:700px;}
	
	
	.ownerIndicator{color:#663}
	.memberIndicator{color:#930}
	.comment_text{display:block;width:100%;max-width:400px;height:90px;}
	/****** Footer Block ******/
	.footer{clear: both;text-align: center;margin: 20px auto;width:100%;}
	.pagefooter{text-align:left; padding:15px; font-size:10px;}
	
	
	.contentbody img{max-width:100%;}
	

	.contentbody td{padding:.1em .2em}
	.video-container {
		position: relative;
		padding-bottom: 56.25%;
		padding-top: 30px; height: 0; overflow: hidden;
	}
	 
	.video-container iframe,
	.video-container object,
	.video-container embed {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}
</style>			