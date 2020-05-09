<?php 
if (isset($_POST["realdel"]){
	mysql_select_db($database_zkizblog, $zkizblog);
	$query_getinfo = "DELETE FROM zb_comment WHERE id = ".$_GET['cid'];
	$getinfo = mysql_query($query_getinfo, $zkizblog) or die(mysql_error());
}
?>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" value="real" name="realdel" />
</form>
<script type="text/javascript">
if (window.confirm('Is it your confirmation.....?')==true){form1.submit();}
</script>