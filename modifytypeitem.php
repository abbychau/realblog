<?php 
require_once('include/common.php'); 
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

$user_id = dbRs("SELECT user_id FROM zb_contenttype WHERE id = :id",['id'=>$_GET['id']]);
if($user_id != $gId){die('Access Denied');}

  dbQuery("UPDATE zb_contenttype SET name=:name WHERE id=:id",['name'=>$_POST['name'],'id'=>$_GET['id']]);
  die("修改成功! 請關掉視窗");
}

$row_gettype = dbRow("SELECT * FROM zb_contenttype WHERE id = :id",['id'=>$_GET['id']]);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modify Type Item</title>
</head>

<body>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <label>
  <input name="name" type="text" value="<?php echo $row_gettype['name']; ?>" />
  </label>
  <label>
  <input type="submit" name="Submit" value="送出" />
  </label>
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>
</html>