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
<script src="https://unpkg.com/turndown/dist/turndown.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<style>
	.container {max-width: none;width:100%}
	.navbar-fixed-top{position:relative}
	body{padding-top:0}
	.panel{margin:0.2em 0}
	
	#editor_index{padding:0}
	#compose_sidebar{
	margin-left: -15px;
    padding: .5em;
    background: #EEE;
    border-top-right-radius: .5em;
	border-bottom-right-radius: .5em;
	}

</style>
<!--
	<script src="http://share.zkiz.com/js/xheditor1.2.1/xheditor-1.2.1.min.js"></script>
	<script src="http://share.zkiz.com/js/xheditor1.2.1/xheditor_lang/zh-tw.js"></script>
	
	<script>
	var editor;
	var overridden = false;//override close window
	
	var allPlugin={
	subscript:{c:'edSubscriptButton',t:'下標:調用execCommand(subscript)'},
	superscript:{c:'edSuperscriptButton',t:'上標:調用execCommand(superscript)'},
	/*
	test4:{c:'edFacebookButton',t:'Facebook 連接 (Ctrl+4)',s:'ctrl+4',h:1,e:function(){
	var _this=this;
	var jTest=$('<iframe src="/ajaxbox/facebook_iframe.php" style="border:0;height:50px"></iframe>');
	_this.showPanel(jTest);
	}},*/
	test5:{c:'edUploadButton',t:'文件上傳(Ctrl+5)',s:'ctrl+5',h:1,e:function(){
	var _this=this;
	var arrMenu=[{s:'文件盒',v:1,t:'ZKIZ members 專用文件盒'},{s:'外部圖片上傳',v:2,t:'外部圖片上傳'}];
	_this.saveBookmark();
	_this.showMenu(arrMenu,function(v){
	if(v==1){
	dialog("http://members.zkiz.com/filebox.php?box=1", "文件盒", true,600);
	}
	if(v==2){
	window.open("http://widgets.zkiz.com/image_upload/");
	}
	});
	}},
	test6:{c:'edUploadButton',t:'文件盒 (Ctrl+6)',s:'ctrl+6',e:function(){
	var _this=this;
	_this.saveBookmark();
	_this.showModal('文件盒','<iframe width="100%" height="100%" border="0" src="http://members.zkiz.com/filebox.php?box=1"></iframe>',800,800);
	}}
	};
	editor=$('#text_content').xheditor(
	{plugins:allPlugin,tools:'full',skin:'o2007silver',width:'1000',height:'<?=isset($_GET['box'])?200:480;?>',hoverExecDelay:100,layerShadow:0,forcePtag:false}
	);
	</script>
-->


<div class="row">
	
	
	
	<div class='col-xs-12 col-sm-3 col-md-3 col-lg-4' id="compose_sidebar">
		
		
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#editor_index" style='border:0;font-weight:bold;padding:0'>文章編輯目錄
					</button>
				</div>

		<div class="navbar-collapse collapse" id="editor_index">
		<form method="get" action="<?=$_SERVER['PHP_SELF'];?>">
			
			<div class="input-group input-group-sm">
				<input type="text" name="modisearch" id="modisearch" value="<?=htmlspecialchars($_GET['modisearch']);?>"  class="form-control"  />
				<div class="input-group-btn">
					<button type='submit' class="btn btn-default">搜索</button>
				</div>
			</div>
			
		</form>
		
		<div class='clear'></div>
		<?php if($viewconlist){?>
			<style>
				
				.small_list td,.small_list th{padding:2px;font-size:small}
			</style>
			
			<table class='table table-default small_list'>
				<thead>
					<th>標題</th>
					<th width='50'>動作</th>
				</thead>
				<?php foreach($viewconlist as $v) { ?>
					<tr>
						<td title="<?=$v['datetime']; ?>">
							
							<? if($gTid == $v['id']){?>
								<span class='glyphicon glyphicon-chevron-right'></span>
							<?}?>
							<a target="_blank" href='/<?=$gUsername;?>/<?=$v['id']; ?>'><?=$v['title']; ?></a>
							
							<small>(<a target="_blank" href='/<?=$gUsername;?>/<?=$v['type_id']; ?>/0'><?=$v['type']; ?></a>) <?=timeago(strtotime($v['datetime']));?></small>
							
						</td>
						
						<td>
							<a href="compose.php?tid=<?=$v['id']; ?>&page=<?=$page;?>"><span class='glyphicon glyphicon-pencil'></span></a>
							
							<a class='delete_confirm' title='刪除' data-blogtitle="<?=$v['title']; ?>" href="compose.php?did=<?=$v['id']; ?>&modisearch=<?=$_GET['modisearch'];?>">
							<span class='glyphicon glyphicon-remove'></span></a>
						</td>
					</tr>
					
				<?php } ?>
			</table>
			<?}else{?>
			沒有文章
		<?}?>
		<p>
			<? generatePagin($page, "compose.php", "page", $totalPages_viewconlist); ?>
			
		</p>
		<a target='_blank' href="//realblog.zkiz.com/modifyentryindex.php">舊改文版面</a>
		
		
		<a class='btn btn-default btn-sm' href='<?=$_SERVER['PHP_SELF'];?>?search=hidden'>搜尋未發佈的文章</a>
		
		
		</div>
		
	</div>
	
	<div class="left_container col-xs-12 col-sm-9 col-md-9 col-lg-8">
		<form name="form1" method="post" action="<?=$editFormAction; ?>" id='form1'>
			<input type='hidden' name='form_action' value='<?=$form_action;?>' />
			<div class="input-group" style='margin-bottom:0.2em'>
				
				<input type="text" name="title" id='qptitle' class='form-control' value="<?=$title;?>" placeholder="標題" required="required" />
				<span class="input-group-addon" style='font-size:0.9em'>
					<select name="type" id='cate_type_list'>
						<?php foreach($gettype as $v) { ?>
							<option value="<?=$v['id']?>" <?php if($v['id']==$row_getcontent['type'] || ($viewconlist[0]['type_id']==$v['id'] && !$row_getcontent['type'])){?>selected="selected"<?}?>><?=$v['name']?></option>
						<?php } ?>
					</select>
				</span>
				<span class="input-group-btn">
					<a class='btn btn-default form-control' onclick='dialog("/ajaxbox/modifytype.php", "文章分類設置", false,400);'><span class="glyphicon glyphicon-th-list"></span></a>
				</span>
				
			</div>
			
			<textarea name="content" id='text_content'><?=$content;?></textarea>
					
					<?if($form_action!='modify'){?>
			<div style='margin-top:3px'>
				<script src='https://www.google.com/recaptcha/api.js'></script>
				<div class="g-recaptcha" data-sitekey="6LdZOgkTAAAAAAOpVl8Bfdky9zdmjho7wSURgBnm"></div>
			</div>
					<?}?>
			<div class="input-group input-group-sm">
				
				<div class="input-group-btn">
					<button type="button" class="btn btn-default" onclick='getMyTags();$("#recent_tags").toggle();'>
						<span class="glyphicon glyphicon-tag"></span>
						<span class="sr-only">看Tag</span>
					</button>
				</div>
				<input class="form-control" type='text' name='tags' id='txttags' value='<?=is_array($thistags)?implode(",",$thistags):"";?>' placeholder='輸入TAG(用, 分隔)' required />
				<div class="input-group-btn">
					<a class='btn btn-default' onclick="extractTags();">自動Tag</a>
					

					<input onclick="overridden=true;" id="content_submit" type="button" class="btn btn-primary  <?php if ($row_getcontent['isshow'] == "-1") {echo "disabled";} ?>" style="width:100px" value="送出" />
					<button type="button" class="btn btn-default" onclick='$("#advancedSettings").toggle();'>
						<span class="caret"></span>
						<span class="sr-only">進階選項</span>
					</button>
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
			
			<div class="panel panel-default" id='advancedSettings'>
				<div class='panel-heading'>
					<h4 class="panel-title">發送設定</h4>
				</div>
				<div class="panel-body">
					
					<label>
						<input <?php if ($row_getcontent['isshow'] ==1 || $form_action == 'compose') {echo "checked=\"checked\"";} ?> <?php if ($row_getcontent['isshow'] == "-1") {echo "disabled  readonly=\"readonly\"";} ?> name="isshow" type="checkbox" value="1" />公開顯示
					</label>
					<label>
						<input <?php if ($row_getcontent['is_page'] == "1") {echo "checked=\"checked\"";} ?> name="is_page" type="checkbox" value="1" />類型為頁面
					</label>
					<? if($form_action=='modify'){?>
						<label><input name="renewtime" type="checkbox" value="1" />更新發表時間至現在</label>
						<input name="tid" type="hidden" value="<?=$gTid; ?>" />
						
						
						<label>
							<input name="renotify" type="checkbox" value="1" />重新通知關注會員
						</label>
						
					<? }?>
					<label>
						顯示方式: 
						<select name="displaymode" id="displaymode">
							<option value="0" <?php if ($row_getcontent['displaymode']=="0") {echo "selected=\"selected\"";} ?>>預設</option>
							<option value="1" <?php if ($row_getcontent['displaymode']=="1") {echo "selected=\"selected\"";} ?>>只顯示標題</option>
							<option value="2" <?php if ($row_getcontent['displaymode']=="2") {echo "selected=\"selected\"";} ?>>顯示全文</option>
						</select>
					</label>
					
						<br />
						<label>
						文章密碼(預設留空、最長32位): 
						<input name="password" type="text" style="width:130px" size="32" value="<?=$row_getcontent['password'];?>" autocomplete="<?=time();?>" />
						</label>
					<br />
					<script>
						
						function toMarkDown(){
							var turndownService = new TurndownService()

							var markdown = turndownService.turndown(easyMDE.value())
							easyMDE.value(markdown)
						}
					</script>
					<a onclick='toMarkDown()'>HTML to MARKDOWN</a>
					<!--
						<iframe src="/ajaxbox/facebook_iframe.php" style="border:0;height:50px"></iframe>
					-->
				</div>
			</div>
			
			
			<a onclick='$("#help").toggle()'>幫助</a>
		</form>	
<pre id='help' style='display:none'>
輸出TeX Mathematics 算式:
<strong>$$</strong>f(a,b,c) = (a^2+b^2+c^2)^3<strong>$$</strong>
TeX 教學可見: https://en.wikibooks.org/wiki/LaTeX/Mathematics
TeX 編輯器可見: http://widgets.zkiz.com/texEditor
-----
高亮程式碼:
&lt;pre class=&quot;prettyprint&quot;&gt;...&lt;/pre&gt;
-----
Responsive Youtube container:
&lt;div class=&quot;video-container&quot;&gt;
&lt;iframe src=&quot;https://www.youtube-nocookie.com/embed/5ynwh1cXU8k?rel=0&amp;amp;showinfo=0&quot; allowfullscreen=&quot;&quot; frameborder=&quot;0&quot; height=&quot;315&quot; width=&quot;100%&quot;&gt;
&lt;/iframe&gt;
&lt;/div&gt;
</pre><div style="width:100%;height:1em"></div>
	</div>
	<!-- LEFT -->
	

	
	
	
</div>







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
	$(window).resize(function(){$(".xheLayout").width($(".left_container").width());});
	setInterval(function(){$(".xheLayout").width($(".left_container").width());},500);
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
			if(easyMDE.value() !=""){
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
		$.get("//realforum.zkiz.com/ajaxdata.php",{"str":$("#qptitle").val(),"type":"extract_tags"},function(data){
			var str = $('#txttags').val().trim() == "" ? data :($('#txttags').val().trim() + "," + data) ;
			
			var tag = str.trim() == "" ? $("#cate_type_list option:selected").text() : str.trim();
			
			$('#txttags').val(tag);
			onComplete();
			return true;
		});
	}
	var easyMDE = new EasyMDE({element: $('#text_content')[0]});
	<?php if ($row_getcontent['isshow'] != "-1") {?>
	$('#content_submit').click(
		function(){
			if(!$('#form1')[0].checkValidity()){$('#form1')[0].reportValidity();return;}
			// $('#text_content').html($('#text_content').value());
			if(easyMDE.value()==""){bootbox.alert("請輸入內文");return false;}
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
	<?} ?>


	$(document).ready(function(){
		getMyTags();
		var _v = findBootstrapEnvironment();
		var _height = (_v=='xs'||_v=='sm'||_v=='md')?($(window).height()-350):Math.min($(window).height()-200,$("#compose_sidebar").height());
		// $('#text_content').summernote(
		// {
		// 	height: _height,
		// 	lang: 'zh-TW', 
		// 	toolbar: [
		// 	['style', ['style']],
		// 	['font', ['bold', 'italic', 'underline', 'clear']],
		// 	['fontname', ['fontname']],
		// 	['color', ['color']],
		// 	['para', ['ul', 'ol', 'paragraph']],
		// 	['height', ['height']],
		// 	['table', ['table']],
		// 	['insert', ['link', 'picture', 'hr','video']],
		// 	['view', ['fullscreen', 'codeview']]
		// 	],
			
		// 	onImageUpload: function(files, welEditable) {
		// 		sendFile(files[0], $(this), welEditable);
		// 	}				
		// }
		// );
		
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