<?php 
require_once('../Connections/zkizblog.php'); 

if(!$isLog){die ("Please Login");}

$gettype = mysql_query("SELECT * FROM zb_contenttype WHERE ownerid = $gId");
$row_gettype = mysql_fetch_assoc($gettype);
$totalRows_gettype = mysql_num_rows($gettype);
?>
<form name="formrb" method="post" action="http://realblog.zkiz.com/compose.php">

<div class=" ui-corner-all" style="padding:2px; margin-bottom:3px;width:100%">

    <select name="type"  style='border:none'>
        <?php do { ?>
        <option value="<?php echo $row_gettype['id']?>"><?php echo $row_gettype['name']?></option>
        <?php
        } while ($row_gettype = mysql_fetch_assoc($gettype));
        $rows = mysql_num_rows($gettype);
        if($rows > 0) {
            mysql_data_seek($gettype, 0);
            $row_gettype = mysql_fetch_assoc($gettype);
        }
        ?>
    </select>
    <input type="text" name="title" style="width:400px;border:none;color:#666;" value="<?=$title==""?"標題":$title;?>"
     onclick="if($(this).val()=='標題'){$(this).val('');}"
     onblur="if($(this).val()==''){$(this).val('標題');}"
     />

</div>

	<textarea name="content" class='' cols="90" rows="25" style='width:100%;height:200px' ></textarea><br />

	<a onclick='document.formrb.submit()' class='button'>送出</a>
	<label><input name="isshow" type="checkbox" value="checkbox" checked="checked" />公開顯示</label>
	<select name="displaymode" id="displaymode">
		<option value="0">不設定顯示方式</option>
		<option value="1">只顯示標題</option>
		<option value="2">全文顯示</option></select>
</form>

<script>
$('.button').button();
</script>
<?php 
mysql_free_result($gettype);
?>