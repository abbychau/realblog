<?php 
	require_once('Connections/zkizblog.php'); 
	
	if(!$isLog){die ("Please Login");}
	
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
		$insertSQL = sprintf("INSERT INTO zb_contenttype (ownerid, name) VALUES (%s, %s)",
		GetSQLValueString($gId, "int"),
		GetSQLValueString($_POST['name'], "text"));
		
		dbQuery($insertSQL);
	}
	
	$arrTypes = dbAr("SELECT * FROM zb_contenttype WHERE ownerid = $gId");
include_once('templatecode/header.php');  ?>
<script>
function wo311(id){
	window.open('modifytypeitem.php?id='+id,'mywindow','width=300,height=100,scrollbars=yes,resizable=yes')
}
</script>

<? foreach($arrTypes as $row_gettype) { ?>
	<?=$row_gettype['name']; ?>
	<a style="cursor:pointer" onClick="wo311(<?=$row_gettype['id']; ?>)">修改</a><br />
<?php } ?>

<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
	<p>分類名:
		<input type="text" name="name" />
		<br />
	</p>
	<p>  
		<input type="submit" name="Submit" value="提交" />
		
	</p>
	
	<input type="hidden" name="MM_insert" value="form1">
</form>
<?php include_once('templatecode/footer.php'); 
	mysql_free_result($gettype);
?>
