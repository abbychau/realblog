<?php 
require_once('Connections/zkizblog.php'); 
if(!$isLog){die ("Please Login");}
$gTid = safe($_GET['tid']);
//$pTid = $gTid==""?:safe($_POST['tid']):$gTid;


$ownerid = dbRs("select ownerid from zb_contentpages a, zb_user b where a.ownerid = b.id AND a.id=$gTid");
if ($gId!=$ownerid){die("Access Denied!");}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//
if ((isset($_POST["form_action"])) && ($_POST["form_action"] == "form1")) {

$query_getcontent = sprintf("SELECT ownerid FROM zb_contentpages WHERE id = %s", $_POST['tid']);
$getcontent = mysql_query($query_getcontent, $zkizblog) or die(mysql_error());
$row_getcontent = mysql_fetch_assoc($getcontent);
if($row_getcontent['ownerid'] != $gId){die('Access Denied');}

	$updateSQL = sprintf("UPDATE zb_contentpages SET title=%s, content=%s, tags=%s,password=%s, displaymode=%s, isshow=%s, type=%s, datetime=%s WHERE id=%s",
		GetSQLValueString($_POST['title'], "text"),
		GetSQLValueString($_POST['content'], "text"),
		GetSQLValueString($_POST['tags'], "text"),
		GetSQLValueString($_POST['password'], "text"),
		GetSQLValueString($_POST['displaymode'], "int"),
		GetSQLValueString(isset($_POST['isshow']) ? "true" : "", "defined","1","0"),
		GetSQLValueString($_POST['type'], "int"),
		isset($_POST['renewtime'])?"NOW()":"datetime",
		GetSQLValueString($_POST['tid'], "int"));

	dbQuery($updateSQL);
	
	header("Location: renew.php?ownerid=$gId");
}


$gettype = dbAr("SELECT * FROM zb_contenttype WHERE ownerid = $gId");

$row_getcontent = dbRow("SELECT * FROM zb_contentpages WHERE id = $gTid");
$appearcus = true;
include_once('templatecode/header.php'); 

?>
<h3>修改文章</h3>
	
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST"
onsubmit='if($("#txttags").val()==""){alert("請輸入至少一個Tag以作分類");return false;}'>
<div class="ui-widget-content ui-corner-all" style="padding:2px; margin-bottom:3px;">


	<select name="type">
	<?php foreach($gettype as $v){ ?>
	<option value="<?=$v['id']?>"<?php if (!(strcmp($v['id'], $row_getcontent['type']))) {echo "selected=\"selected\"";} ?>><?=$v['name']?></option>
	<?php } ?>

	</select>
<input name="title" type="text" style="width:400px;border:none;color:#666"  value="<?php echo $row_getcontent['title']; ?>" />	

</div>


<script type="text/javascript" src="/js/jquery.tagsinput.js"></script>
<script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
//<![CDATA[
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas({fullPanel : true}) });
//]]>
</script>
<textarea name="content" cols="90" rows="25"><?php echo htmlentities($row_getcontent['content'],ENT_COMPAT, "UTF-8"); ?></textarea>

	<div id="correctPos" style='width:700px'>
	<h3 id='h33'><a href="#">文件盒(上傳)</a></h3>
	<div style='overflow:hidden; padding:0;'>
		<iframe width='696' height='370' style='border:0' src='' id='filebox'></iframe>
	</div>

	</div>

<p>

<label><input <?php if (!(strcmp($row_getcontent['isshow'],1))) {echo "checked=\"checked\"";} ?> name="isshow" type="checkbox" value="checkbox" />公開顯示</label> | 
<label><input name="renewtime" type="checkbox" value="checkbox" />更新發表時間至現在</label> | 
<select name="displaymode" id="displaymode">
	<option value="0" <?php if ($row_getcontent['displaymode']=="0") {echo "selected=\"selected\"";} ?>>不設定顯示方式</option>
	<option value="1" <?php if ($row_getcontent['displaymode']=="1") {echo "selected=\"selected\"";} ?>>只顯示標題</option>
	<option value="2" <?php if ($row_getcontent['displaymode']=="2") {echo "selected=\"selected\"";} ?>>全文顯示</option>
</select> | 
文章密碼(預設留空、最長32位): 
<input name="password" type="password" value="<?=$row_getcontent['password'];?>" />

<input type='text' name='tags' id='txttags' size='50' value='<?=$row_getcontent['tags'];?>'/>
	快速加入: 
	<?php foreach($gettype as $row_gettype) { ?>
    <a style='cursor:pointer' onclick="$('#txttags').addTag('<?php echo $row_gettype['name']?>');"><?php echo $row_gettype['name']?></a>
    <?php } ?><br />
<input name="tid" type="hidden" value="<?php echo $_GET['tid']; ?>" />

</p>
<input type="submit" name="Submit" value="送出" class='button' />
<input type="hidden" name="form_action" value="form1">

</form>
<br />
<script>
$('#txttags').tagsInput();
$( "#correctPos" ).accordion({
	autoHeight: false,
	collapsible: true,
	active: false
});
//$( "#correctPos" ).accordion("activate" , false);
$( "#correctPos" ).bind("accordionchange", function(event, ui) {
		
	if($(ui.newHeader).attr('id') == 'h32'){
	$('#drawing').attr('src','http://realforum.zkiz.com/images/fddraw.swf?maxtext=90000');
	}
	
	if($(ui.newHeader).attr('id') == 'h33'){
	$('#filebox').attr('src','http://members.zkiz.com/filebox.php?box=1');
	}
});
function del_cate(itid){
	$.post(
		"/ajaxbox/modifytype.php", 
		{ action: 'del_cate', tid : itid },
		function(data){
			if(data!='Deleted.'){
				alert(data);
			}else{
				$("#cate_type_list option[value='"+itid+"']").remove();
				$("#tr_row_"+itid).remove();
			}
		}
	);
}
function add_cate(){
	if($("#cate_name").val() == ""){return false;}
	$.post(
		"/ajaxbox/modifytype.php", 
		{ action: 'insert_cate', name : $("#cate_name").val() },
		function(data){
			var tr = "<tr><td>"+data.name+"</td><td> </td><td> </td></tr>";
			$("#cate_list_table").append(tr);
			$("#cate_type_list").append("<option value='"+data.id+"'>"+data.name+"</option>");
		},
		"json"
	);
}
</script>
<?php include_once('templatecode/footer.php'); ?>