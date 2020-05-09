</div><!--end of container-->

<?php if(!$noSidebar){?>
	<div class="sidebar col-xs-12 col-sm-4 col-lg-4">
		<?php include_once(dirname(__FILE__) . '/sidebar.php'); ?>
		<div style="clear:both"></div>
	</div>
<?php } ?>


<div class="footer">
	<?=$blogfooter; ?>
</div>

<div class="pagefooter" id="copyfooter">
	
    <div class='left'>
        Powered by <a href='http://realblog.zkiz.com'><strong>RealBlog</strong></a>
		<?=logEndTime();?>ms  (Q=<?=sizeof($queryRecord);?> + R=<?=sizeof($redisRecord);?>) 
		
		<div class="btn-group dropup">
			<button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
				分站 <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				
				
				<li><a href="http://www.zkiz.com" class='withNote'>Home</a></li>
				<li class="divider"></li>
				<li><a href="http://realforum.zkiz.com" class='withNote'>RealForum</a></li>
				<li><a href="http://realblog.zkiz.com" class='withpencil'>RealBlog</a></li>
				<li><a href="http://stock.zkiz.com" class='withSuitcase'>Stock</a></li>
				<li><a href="http://ec.zkiz.com" class='withNote'>Endless Choice</a></li>
				<li><a href="http://fancy.zkiz.com" class='withpencil'>Fancy Buzz</a></li>
				<li><a href="http://gloomy.zkiz.com" target="_blank">Gloomy Sunday</a></li>
				<li><a href="http://wiki.zkiz.com" target="_blank">ZKIZ Wiki</a></li>
			</ul>
		</div>
		
	</div>
	
	<!--
	<a href="http://www.beyondsecurity.com/vulnerability-scanner-verification/realblog.zkiz.com"><img src="https://secure.beyondsecurity.com/verification-images/realblog.zkiz.com/vulnerability-scanner-2.gif" alt="Website Security Test" border="0" /></a>
	-->
	<div class="clear"></div>
</div>
<script src="https://cdn.jsdelivr.net/bootstrap.material-design/0.5.10/js/material.min.js"></script>
<script src="https://cdn.jsdelivr.net/bootstrap.material-design/0.5.10/js/ripples.min.js"></script>
<script>
    $.material.init();
</script>
</body>
</html>
<!--
	<? if($gId==1){ print_r($queryRecord);}?>
-->