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