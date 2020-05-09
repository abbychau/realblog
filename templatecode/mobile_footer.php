
<div class="sidebar">

	<div class="cussidebar">
	<?php if ($sidebar!=""){echo $sidebar;} ?>

	<?php if($appearcus==true){?>
	<a href="http://widgets.zkiz.com"><img src="http://share.zkiz.com/official/widad.png" alt="wid ad" /></a>
	<hr />
	<?if($fbme){?>
	Connected to Facebook as <br /><a href='<?=$fbme['link'];?>'><strong><?=$fbme['name'];?></strong></a>
	<?}else{?>
	<a href='<?=$facebook->getLoginUrl(array("read_stream","publish_stream"));?>'>
	Connect to Facebook
	</a>

	<?}?>
	<?php } ?>
	</div>

	<div class="syssidebar">
		<?php if ($sidebar2!=""){echo $sidebar2;} ?>
	</div>

	<div style="clear:both"></div>
</div>
<div style="clear:both"></div>
</div> <!-- end innerwrapper -->


<div class="footer">
	<?=$blogfooter; ?>
</div>

</div>
Powered by <a href='http://realblog.zkiz.com'><strong>RealBlog</strong></a>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-4293967-12");
pageTracker._setDomainName(".zkiz.com");
pageTracker._trackPageview();
} catch(err) {}</script>
<script  type="text/javascript">
$(".button").button().css("font-weight","normal");
</script>
</body>
</html>
