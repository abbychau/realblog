<?if (isset($postedURL)){?>
	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>己成功<?=$modified?"修改":"發布";?>文章!</strong> 可以<a href='<?=$postedURL;?>' target='_blank'>按此</a>到剛發布的文章頁面。
	</div>
<?}?>

<?php if(!isset($_GET['box']) && false){?>
	<ol class="breadcrumb">
		<li><a href="/"><span class='glyphicon glyphicon-home'></span> RealBlog</a></li>
		<li class="active"><?php if($form_action == 'compose'){?>發文<?}else{?>修改<?}?></li>
	</ol>
	
<?}?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<style>
	.container {max-width: none;width:100%}
	.navbar-fixed-top{position:relative}
	body{padding:0;margin:0}
	.panel{margin:0.2em 0}
	
	#editor_index{padding:0}
	#compose_sidebar{
	margin-left: -15px;
    padding: .5em;
    background: #EEE;
    border-top-right-radius: .5em;
	border-bottom-right-radius: .5em;
	}
	#copyfooter{display:none}
	#rb_navbar{display:none}
	.container{margin:0;padding:.5em}
	.footer{display:none}
	.col-xs-12,.col-sm-12,.col-lg-12{margin:0;padding:0}
</style>


		<form name="form1" method="post" action="<?=$editFormAction; ?>" id='form1'>
			<input type='hidden' name='form_action' value='<?=$form_action;?>' />
			<div class="input-group" style='margin-bottom:0.2em'>
				
				<input type="text" name="title" id='qptitle' class='form-control' value="<?=$title;?>" placeholder="標題" required />
				<span class="input-group-addon" style='font-size:0.9em'>
					<select name="type" id='cate_type_list'>
						<?php foreach($gettype as $v) { ?>
							<option value="<?=$v['id']?>" <?php if($v['id']==$row_getcontent['type'] || ($viewconlist[0]['type_id']==$v['id'] && !$row_getcontent['type'])){?>selected="selected"<?}?>><?=$v['name']?></option>
						<?php } ?>
					</select>
				</span>				
			</div>
			
			<textarea name="content" id='text_content'><?=$content;?></textarea>
			
			<div class="input-group input-group-sm">

				<input class="form-control" type='text' name='tags' id='txttags' value='<?=is_array($thistags)?implode(",",$thistags):"";?>' placeholder='輸入TAG(用, 分隔)' />
				<div class="input-group-btn">
					<a class='btn btn-default' onclick="extractTags();">自動Tag</a>
					<input onclick="overridden=true;" id="content_submit" type="button" class="btn btn-primary " style="width:100px" value="快速發文" />
					
				</div>
			</div>
			<div class="panel panel-default" id='recent_tags' style='display:none'>
				<div class="panel-body">
					<div id='mytags' class='clear'>
						<a class='button' onclick="getMyTags()">取得我的用過的Tags</a>
					</div>
				</div>
				
			</div>
			
			
			<div class="input-group input-group-sm">
				
			</div>
			
		</form>	





<div class='hide'>
	
	<?php foreach($gettype as $v) { ?>
		<a style='cursor:pointer' onclick="$('#txttags').addTag('<?=$v['name']?>');"><?=$v['name']?></a>
	<?php } ?>
	
</div>
<script>
	var overridden = false;//override close window
function findBootstrapEnvironment() {
    var envs = ['xs', 'sm', 'md', 'lg'];

    var $el = $('<div>');
    $el.appendTo($('body'));

    for (var i = envs.length - 1; i >= 0; i--) {
        var env = envs[i];

        $el.addClass('hidden-'+env);
        if ($el.is(':hidden')) {
            $el.remove();
            return env;
        }
    }
}
	$(".delete_confirm").on("click", function(e) {
		var link = this;
		
		e.preventDefault();
		bootbox.confirm("你確定要刪除["+$(this).attr('data-blogtitle')+"]嗎? <br />(這個操作不能復原)", function(r) {
			if (r == true) {
				window.location = link.href;
			}
		}); 
	});
	
	
	<? if(!isset($_GET['tid'])){ ?>
		window.onbeforeunload=function() {
			if($("#text_content").html() !="" || $('#text_content').code() != ""){
				if(overridden != true){
					return '你的文章還沒儲存, 確定要離開嗎?';
				}
			}
		}
	<? } ?>
	
	function del_cate(itid){
		$.post(
		"/ajaxbox/modifytype.php", 
		{ action: 'del_cate', tid : itid },
		function(data){
			if(data!='Deleted'){
				bootbox.alert(data);
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
	function getMyTags(){
		$.getJSON(
		"/ajaxdata.php",
		{type:"mytags"},
		function(data){
			
			var items = [];
			var str = "";
			$.each(data, 
			function(key, val) {
				str += '<a>' + val.tag + '</a> ';
				
			}
			
			);
			
			$('#mytags').html(str);
			
			$('#mytags a').click(
			function(){
				$('#txttags').append($(this).html());
			}
			);
		}
		);
	}
	function extractTags(onComplete){
		if($("#qptitle").val() == ""){
			bootbox.alert("請先填入標題");
			return false;
		}
		$.get("http://realforum.zkiz.com/ajaxdata.php",{"str":$("#qptitle").val(),"type":"extract_tags"},function(data){
			var str = $('#txttags').val().trim() == "" ? data :($('#txttags').val().trim() + "," + data) ;
			
			var tag = str.trim() == "" ? $("#cate_type_list option:selected").text() : str.trim();
			
			$('#txttags').val(tag);
			onComplete();
			return true;
		});
	}
	$('#content_submit').click(
		function(){
			$('#text_content').html($('#text_content').code());
			if($('#text_content').code()==""){bootbox.alert("請輸入內文");return false;}
			if($("#txttags").val()==""){
				$("#txttags").val($("#cate_type_list option:selected").text());
				window.onbeforeunload=null;
				extractTags(function(){
					$("#form1").submit();
				});
			}else{
				$("#form1").submit();
			}
		}
	);
	$(document).ready(function(){
		getMyTags();
		var _v = findBootstrapEnvironment();
		var _height = ($(window).height()-130);
		$('#text_content').summernote(
		{
			height: _height,
			lang: 'zh-TW', 
			toolbar: [
			['font', ['bold', 'italic', 'underline', 'clear']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['insert', ['link', 'picture', 'hr','video']]
			],
			disableResizeEditor : true,
			onImageUpload: function(files, welEditable) {
				sendFile(files[0], $(this), welEditable);
			}				
		}
		);
		
        function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("Filedata", file);
            $.ajax({
                data: data,
                type: "POST",
                url: "/ajaxbox/uploadimage.php",
                cache: false,
                contentType: false,
                processData: false,
                success: function(url) {
					var filename = url.substring(url.lastIndexOf('/')+1);
					editor.summernote("insertImage", url, filename); 
				}
			});
		}
		
	});
</script>