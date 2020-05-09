<? include(dirname(__FILE__) . "/normal_header.php"); 	?>
		<script type="text/x-mathjax-config">
			MathJax.Hub.Config({
				tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
			});
		</script>
		<script type="text/javascript"
		src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
		</script>
<div class="page-header">
<h1><a href="/<?=$blogInfo['username']; ?>"><?=$blogInfo['blogname']; ?></a> </h1>
    <p class="slogan"><?=$blogInfo['slogan'];?></p>
</div>

<div class="topbar">
<?=$blogInfo['topbar'];?>
</div>
<div class="mainpart">
	<?
	include(dirname(__FILE__) . "/normal_{$mainTemplate}.php"); 
	?>
</div>

<? include(dirname(__FILE__) . "/normal_footer.php");  ?>