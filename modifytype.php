<?php 
require_once('Connections/zkizblog.php'); 
if(!$isLog){die ("Please Login");}
if(!$gId){
	header("location: activate.php");
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO zb_contenttype (ownerid, name) VALUES (%s, %s)",
	   GetSQLValueString($gId, "int"),
	   GetSQLValueString($_POST['name'], "text"));

  
  $Result1 = mysql_query($insertSQL, $zkizblog) or die(mysql_error());
}

$gettype = mysql_query("SELECT * FROM zb_contenttype WHERE ownerid = $gId");
$row_gettype = mysql_fetch_assoc($gettype);
$totalRows_gettype = mysql_num_rows($gettype);

$appearcus = true;

include_once('templatecode/header.php'); ?>
<h3>修改分類</h3>
<?php do { ?>
  <?php echo $row_gettype['name']; ?> <a style="cursor:pointer" onClick="window.open('modifytypeitem.php?id=<?php echo $row_gettype['id']; ?>','mywindow','width=300,height=100,scrollbars=yes,resizable=yes')">修改</a> 
  <a href="deletetype.php?tid=<?php echo $row_gettype['id'];?>">刪除</a> <br />
  <?php } while ($row_gettype = mysql_fetch_assoc($gettype)); ?>
<br />
<br />

<h3>新增分類</h3>
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
<?php include_once('templatecode/footer.php'); ?>
