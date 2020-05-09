<?php 
require_once('Connections/zkizblog.php'); 


if(!$isLog){die ("Please Login");}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if(is_numeric($_GET['rftid']) && isset($_GET['rftid'])){
$tid = safe($_GET['rftid']);
	$title = dbRs("SELECT title FROM zf_contentpages WHERE id = $tid");
	
	$content = dbRs("SELECT content FROM zf_reply WHERE isfirstpost = 1 AND fellowid = $tid");
	$content = nl2br(strip_tags(str_replace(array('[',']'), array('<','>'), $content)))."<br />原帖URL: http://realforum.zkiz.com/thread.php?tid=$tid";
	
}
if ($_POST["title"] != "") {

	$insertSQL = sprintf("INSERT INTO zb_contentpages (ownerid, title, content,tags, isshow, displaymode, type, datetime) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
		GetSQLValueString($gId, "int"),
		GetSQLValueString($_POST['title'], "text"),
		GetSQLValueString($_POST['content'], "text"),
		GetSQLValueString($_POST['tags'], "text"),
		GetSQLValueString(isset($_POST['isshow']) ? "true" : "", "defined","1","0"),
		GetSQLValueString($_POST['displaymode'], "int"),
		GetSQLValueString($_POST['type'], "int"),
		GetSQLValueString(gmdate("Y-m-d H:i:s", time()+28800), "date"));
		
	$Result1 = mysql_query($insertSQL, $zkizblog) or die(mysql_error());
	
	if($fbme && isset($_POST['isshow'])){
		
		$nulls = array(" ", "\n", "\r", "　", "\t", "&nbsp;", "'");
	
		$statusUpdate = $facebook->api('/me/feed', 'post', array(
			'message'=> safe($_POST['title']), 
			'picture'=> 'http://share.zkiz.com/official/RB.jpg', 
			'description'=> mb_substr(str_replace($nulls,"",trim(strip_tags($_POST['content'], "text"))),0,200,"utf8")."...", 
			'link' => "http://realblog.zkiz.com/$gUsername/".mysql_insert_id()
		));
	}
	
	addNews($gUsername,safe($_POST['title']),2,"http://realblog.zkiz.com/$gUsername/".mysql_insert_id());
	header("Location: renew.php?ownerid=$gId");
}

$gettype = dbAr("SELECT * FROM zb_contenttype WHERE ownerid = $gId");

if(isset($_GET['box'])){
$background = ".sidebar,.navPanel,.pagefooter{display:none;} .innerwrapper{width:720px;} .outerwrapper {margin-top: 0px;}";
}


$appearcus = true;
include_once('templatecode/header.php');
?>
<h3>寫新文章</h3>
<form name="form1" method="post" action="<?php echo $editFormAction; ?>" 
onsubmit='if($("#txttags").val()==""){alert("請輸入至少一個Tag以作分類");return false;}'>
<div class="ui-widget-content ui-corner-all" style="padding:2px; margin-bottom:3px;">
    

    <select name="type">
        <?php foreach($gettype as $row_gettype) { ?>
        <option value="<?php echo $row_gettype['id']?>"><?php echo $row_gettype['name']?></option>
        <?php } ?>
    </select>
    <input type="text" name="title" style="width:400px;border:none;color:#666" value="<?=$title==""?"標題":$title;?>"
     onclick="if($(this).val()=='標題'){$(this).val('');}"
     onblur="if($(this).val()==''){$(this).val('標題');}"
     />

</div>

	<?php if($_GET['phone']!=1){ ?>
	<script type="text/javascript" src="/js/fckeditor/fckeditor.js"></script>
	<script type="text/javascript" src="/js/jquery.tagsinput.js"></script>
	<script type="text/javascript">
	window.onload = function()
	{
		// Automatically calculates the editor base path based on the _samples directory.
		// This is usefull only for these samples. A real application should use something like this:
		// oFCKeditor.BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
		var sBasePath = '/js/fckeditor/' ;
	
		var oFCKeditor = new FCKeditor( 'content' ) ;
		oFCKeditor.BasePath	= sBasePath ;
		oFCKeditor.Height	= 500 ;
		oFCKeditor.Width	= 700 ;
		oFCKeditor.ReplaceTextarea() ;
	}
	
	</script>
	<?php } ?>
	<textarea name="content" cols="90" rows="25"><?=$content;?></textarea><br />
	
	<div id="correctPos" style='width:700px'>
	<h3 id='h33'><a href="#">文件盒(上傳)</a></h3>
	<div style='overflow:hidden; padding:0;'>
		<iframe width='696' height='370' style='border:0' src='' id='filebox'></iframe>
	</div>









	</div>
	
			<p>
	
	<label>
	公開顯示: 
	<input name="isshow" type="checkbox" value="checkbox" checked="checked" />
	</label> |
	顯示方式: 
	<select name="displaymode" id="displaymode">
		<option value="0">不設定顯示方式</option>
		<option value="1">只顯示標題</option>
		<option value="2">全文顯示</option>
	</select>

	<input type='text' name='tags' id='txttags' size='50' />
	快速加入: 
	<?php foreach($gettype as $row_gettype) { ?>
    <a style='cursor:pointer' onclick="$('#txttags').addTag('<?php echo $row_gettype['name']?>');"><?php echo $row_gettype['name']?></a>
    <?php } ?>
	</p>
	<input type="submit" name="Submit" value="送出" class='button' />

</form>
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
</script>
<p>&nbsp;</p>
<?php 
include_once('templatecode/footer.php'); 
?>