<?php
require_once('include/common.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $id = dbQuery(
    "INSERT INTO zb_user (username, password, slogan, email, blogname) VALUES (?,?,?,?,?)",
    [
      $_POST['username'],
      $_POST['password'],
      "這是我的Blog!",
      $_POST['email'],
      $_POST['blogname']
    ]
  );

  dbQuery("INSERT INTO zb_contenttype (user_id, name) VALUES ($id, \"預設分類\")");
  die("已成功註冊! 請點<a href='http://realblog.zkiz.com'>這裡</a>跳回首頁!");
}
$htmltitle = "Real Blog - 註冊";
include_once('templatecode/header.php'); ?>
<script type="text/javascript">
  function validateUsername(fld) {
    var illegalChars = /\W/;
    if (illegalChars.test(fld.value)) {
      alert('用戶名只可包含數字、英文或底線!');
      return false;
    } else {
      return true;
    }
  }
</script>
<form name="form1" action="<?php echo $editFormAction; ?>" method="POST" onsubmit="return validateUsername(this.username)">
  <h4>註冊</h4>
  <table width="364" border="0">
    <tr>
      <td width="116" align="right">使用者名稱:</td>
      <td width="238"><input type="text" name="username" /></td>
    </tr>
    <tr>
      <td align="right">密碼:</td>
      <td><input type="password" name="password" /></td>
    </tr>
    <tr>
      <td align="right">E-mail:</td>
      <td>
        <input type="text" name="email" /> </td>
    </tr>
    <tr>
      <td align="right">Blog 名稱: </td>
      <td><label>
          <input type="text" name="blogname" />
        </label></td>
    </tr>
    <tr>
      <td></td>
      <td align="left">
        <input type="submit" name="Submit" value="提交註冊" />
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php include_once('templatecode/footer.php'); ?>