<?php 
require_once('../include/common.php'); 
if(!$isLog){die ("Please Login");}

if ($_POST["action"] == "insert_cate") {
	$id = dbQuery("INSERT INTO zb_contenttype (ownerid, name) VALUES ($gId, :name)",['name'=>$_POST['name']]);
	echo json_encode(array("id"=>$id, "name"=> $_POST['name']));
	exit;
}

if($_POST['action'] == 'del_cate'){
	$tid = intval($_POST['tid']);
	if($tid==0){die("Access Denied.");}

	$numArticle = dbRs("SELECT count(*) FROM zb_contentpages WHERE type = $tid");
	if ($numArticle>0){die("This category has $numArticle articles left, deletion is not allowed.");}

	$owner_id = dbRs("SELECT ownerid FROM zb_contenttype WHERE id = $tid");
	if($owner_id != $gId){die('Access Denied');}
	dbQuery("DELETE FROM zb_contenttype WHERE id=$tid");
	die("Deleted");
}

$gettype = dbAr("SELECT * FROM zb_contenttype WHERE ownerid = $gId");

?>
<script>
	function wo(id){
		window.open('/ajaxbox/modifytypeitem.php?id='+id,'mywindow','width=300,height=100,scrollbars=yes,resizable=yes');
	}
</script>
<table id='cate_list_table'>
<?php foreach($gettype as $row_gettype) { ?>
<tr id='tr_row_<?php echo $row_gettype['id'];?>'>

<td style='padding:2px 1em 2px 2px'>
	<?php echo $row_gettype['name']; ?> 
</td><td>
	<button onClick="wo(<?=$row_gettype['id']; ?>);"><i class="glyphicon glyphicon-pencil"></i> 修改</button> 
	<button onClick="del_cate(<?=$row_gettype['id'];?>);"><i class="glyphicon glyphicon-trash"></i> 刪除</button>
</td>

</tr>
<?php } ?>
<tr>
	<td style='padding:2px 1em 2px 2px'>新增:</td>
	<td><input type="text" name="name" id='cate_name' placeholder='輸入名稱' /><button onclick='add_cate()'>提交</button></td>
</tr>
</table>


