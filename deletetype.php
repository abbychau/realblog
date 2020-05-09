<?php require_once('Connections/zkizblog.php'); 
if(!$isLog){die ("Please Login");}

if ((isset($_GET['did'])) && ($_GET['did'] != "")) {

$query_getcontent = sprintf("SELECT ownerid FROM zb_contenttype WHERE id = %s", $_GET['did']);
$getcontent = mysql_query($query_getcontent, $zkizblog) or die(mysql_error());
$row_getcontent = mysql_fetch_assoc($getcontent);
if($row_getcontent['ownerid'] != $gId){die('Access Denied');}

  $deleteSQL = sprintf("DELETE FROM zb_contenttype WHERE id=%s",
                       GetSQLValueString($_GET['did'], "int"));

  
  $Result1 = mysql_query($deleteSQL, $zkizblog) or die(mysql_error());

  $deleteGoTo = "modifytype.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_gettitle = "-1";
if (isset($_GET['tid'])) {
  $colname_gettitle = (get_magic_quotes_gpc()) ? $_GET['tid'] : addslashes($_GET['tid']);
}

$query_gettitle = sprintf("SELECT name FROM zb_contenttype WHERE id = %s", $colname_gettitle);
$gettitle = mysql_query($query_gettitle, $zkizblog) or die(mysql_error());
$row_gettitle = mysql_fetch_assoc($gettitle);
$totalRows_gettitle = mysql_num_rows($gettitle);

$query_getNoOfPages = sprintf("SELECT count(*) noof FROM zb_contentpages WHERE type = %s", $colname_gettitle);
$getNoOfPages = mysql_query($query_getNoOfPages, $zkizblog) or die(mysql_error());
$row_getNoOfPages = mysql_fetch_assoc($getNoOfPages);
$totalRows_getNoOfPages = mysql_num_rows($getNoOfPages);
 include_once('templatecode/header.php'); ?>
<h3>刪除類別</h3>
<h4>你決定要刪除這個類別嗎?</h4>
<p><?php echo $row_gettitle['name']; ?><br />
裡面有<?php echo $row_getNoOfPages['noof'];?>篇文章
</p>
<?php if ($row_getNoOfPages['noof']>0){?>
注意: 這個類別還有文章, 限制不被刪除!
<?php }else{ ?>
<a href="deletetype.php?did=<?php echo $colname_gettitle; ?>">確定</a> <a href="modifytype.php">返回</a>
<?php }  include_once('templatecode/footer.php'); 
mysql_free_result($gettitle);

mysql_free_result($getNoOfPages);
?>