<?php
header('X-XSS-Protection:0');
require_once('Connections/zkizblog.php');

if (!$isLog) {
	screenMessage("Please Login", "You must login to set your info.", "/");
}
if (!$gId) {
	header("location: activate.php");
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//$fb = getFacebook();
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$params=[
		'slogan' => $_POST['slogan'],
		'email' => $_POST['email'],
		'blogname' => $_POST['blogname'],
		'mobilebg' => $_POST['mobilebg'],
		'background' => $_POST['background'],
		'topbar' => $_POST['topbar'],
		'sidebar' => $_POST['sidebar'],
		'footer' => $_POST['footer'],
		'displaytype' => $_POST['displaytype'],
		'displaynum' => $_POST['displaynum'],
		'blogframe' => $_POST['blogframe'],
		'bootstrap_theme' => $_POST['bootstrap_theme'],
		'blacklist' => $_POST['blacklist'],
		'comment_system1' => $_POST['comment_system1'] == 1 ? 1 : 0,
		'comment_system2' => $_POST['comment_system2'] == 1 ? 1 : 0,
		'comment_system3' => $_POST['comment_system3'] == 1 ? 1 : 0,
		'disqus_name' => $_POST['disqus_name'],
		'default_content_encoding' => $_POST['default_content_encoding'],
		'id' => $gId
	];
	dbAr(
		"UPDATE zb_user SET 
            slogan=:slogan, 
            email=:email, 
            blogname=:blogname, 
            mobilebg=:mobilebg,
            background=:background, 
            topbar=:topbar, 
            sidebar=:sidebar, 
            footer=:footer, 
            displaytype=:displaytype, 
            displaynum=:displaynum, 
            blogframe=:blogframe, 
            bootstrap_theme=:bootstrap_theme, 
            blacklist=:blacklist,
            comment_system1=:comment_system1,
            comment_system2=:comment_system2,
            comment_system3=:comment_system3,
            disqus_name=:disqus_name, 
            default_content_encoding=:default_content_encoding 
            WHERE 
            id=:id",
		$params
	);
}

$blogInfo = dbRow("SELECT * FROM zb_user WHERE id = {$gId}");

$appearcus = true;

include_once('templatecode/header.php');
?>
<script>
	$(document).ready(function() {
		var TA_resizer = function() {
			this.style.overflow = 'hidden';
			this.style.height = '24px';
			this.style.height = this.scrollHeight + 12 + 'px';
		};
		$('textarea').on('keyup', TA_resizer);
		$('textarea').each(TA_resizer);
	});
</script>
<style>
	textarea {
		font-size: 9pt;
	}

	table td {
		padding: 3px
	}
</style>

<div class="">
	<div class="">
		<h3>修改信息</h3>

		<form id="form1" class="form-horizontal" method="POST" action="<?php echo $editFormAction; ?>">
			<h4>標語</h4>
			<textarea style="margin:10px 10px 10px 25px;" name="slogan" cols="90" rows="2"><?php echo $blogInfo['slogan']; ?></textarea>
			<h4>基本信息</h4>
			<div class="well">
				<div class="form-group row">
					<label for="username" class="col-md-2 control-label">使用者名稱</label>
					<div class="col-md-10">
						<input class="form-control" name="username" type="text" value="<?php echo $blogInfo['username']; ?>" disabled="disabled" />
					</div>
				</div>
				<div class="form-group row">
					<label for="password" class="col-md-2 control-label">密碼</label>
					<div class="col-md-10">
						<a class="form-control" href="http://members.zkiz.com/modifyinfo.php">修改Password</a>
					</div>
				</div>
				<div class="form-group row">
					<label for="email" class="col-md-2 control-label">E-mail</label>
					<div class="col-md-10">
						<input class="form-control" name="email" type="text" value="<?php echo $blogInfo['email']; ?>" />
					</div>
				</div>
				<div class="form-group row">
					<label for="blog" class="col-md-2 control-label">Blog 名稱</label>
					<div class="col-md-10">
						<input class="form-control" name="blogname" type="text" value="<?php echo $blogInfo['blogname']; ?>" />
					</div>
				</div>


			</div>



			<h4>版面設置</h4>
			<div class="well">

				<div class="form-group">
					<label for="mobilebg" class="control-label">手機版背景</label>


					<input name="mobilebg" type="text" class="form-control" value="<?php echo $blogInfo['mobilebg']; ?>" placeholder="(e.g.http://what.com/image.jpg)" size="50" />
				</div>
				<div class="form-group">
					<label for="background" class="control-label">CSS</label>

					<textarea name="background" class="form-control" cols="90" rows="20"><?php echo $blogInfo['background']; ?></textarea>
				</div>

				Notice: CSS 語法<a href="javascript:" onclick="$('#tips').toggle('fast')">按我</a>看示例(body)

				<div style="display:none" id="tips" class='well'>
					<fieldset>
						<legend>示例</legend>
						<p>body{<br />
							background-color:#000000;<br />
							background-image:url(http://l.yimg.com/e/style/1/19/matrix-bodybg.jpg);<br />
							background-repeat:repeat-x;<br />
							background-position: top;<br />
							color:#CCFFCC;<br />
							}</p>
					</fieldset>
					<fieldset>
						<legend>解說</legend>
						<p>background-color:<u><em>#000000</em></u>; <strong><span style="color: rgb(0, 51, 0);"><br />背景色, 這個為RGB 碼 eg. #FF0000 為紅</span></strong><br />
							background-image:url(<u><em>http://l.yimg.com/e/style/1/19/matrix-bodybg.jpg</em></u>); <strong><span style="color: rgb(0, 51, 0);"><br />背景圖網址, 不需要的話可以移除這行</span></strong><br />
							background-repeat:<u><em>repeat-x</em></u>; <strong><span style="color: rgb(0, 51, 0);"><br />背景是否重覆, repeat-x為只橫向重覆; </span></strong><strong><span style="color: rgb(0, 51, 0);">repeat-y 為縱向; repeat 重覆; no-repeat 為不重覆;<br />
								</span></strong>background-position:<u><em>top</em></u>; <strong><span style="color: rgb(0, 51, 0);"><br />首張背景的位置eg. top right 即為右上方, bottom left 是左下方</span></strong><strong><span style="color: rgb(0, 51, 0);"><br />
								</span></strong>color:<u><em>#CCFFCC</em></u>; <strong><span style="color: rgb(0, 51, 0);"><br />字體色, 這個為RGB 碼 #CCFFCC 為淺綠</span></strong><strong><span style="color: rgb(0, 51, 0);"><br />
								</span></strong></p>
					</fieldset>
				</div>


				<div class="form-group">
					<label for="topbar" class="control-label">頁面上方代碼</label>
					<textarea class="form-control" name="topbar" cols="90" rows="5"><?php echo $blogInfo['topbar']; ?></textarea>

					<label for="footer" class="control-label">頁面底部代碼</label>
					<textarea class="form-control" name="footer" cols="90" rows="5"><?php echo $blogInfo['footer']; ?></textarea>
				</div>

				<div class="form-group">
					<label for="sidebar" class="control-label">邊列代碼:</label>
					<textarea class="form-control" name="sidebar" cols="90" rows="5"><?php echo $blogInfo['sidebar']; ?></textarea>
				</div>
			</div>
			<h4>顯示設置</h4>
			<div style="padding-left:25px;line-height:2em">
				Blog顯示形式:
				<select name="displaytype">
					<option value="1" <?php if (!(strcmp(1, $blogInfo['displaytype']))) {
											echo "selected=\"selected\"";
										} ?>>顯示標題及所有內容</option>
					<option value="2" <?php if (!(strcmp(2, $blogInfo['displaytype']))) {
											echo "selected=\"selected\"";
										} ?>>只顯示標題</option>
					<option value="3" <?php if (!(strcmp(3, $blogInfo['displaytype']))) {
											echo "selected=\"selected\"";
										} ?>>顯示標題和首100字</option>
					<option value="4" <?php if (!(strcmp(4, $blogInfo['displaytype']))) {
											echo "selected=\"selected\"";
										} ?>>圖庫</option>
				</select><br />
				每頁顯示文章:
				<select name="displaynum">
					<? for ($i = 1; $i <= 12; $i++) { ?>
						<option value="<?= $i ?>" <?php if (!(strcmp($i, $blogInfo['displaynum']))) {
													echo "selected=\"selected\"";
												} ?>><?= $i ?></option>
					<? } ?>
				</select>篇<br />
				模板:
				<select name="blogframe">
					<option value="1" <?php if (!(strcmp(1, $blogInfo['blogframe']))) {
											echo "selected=\"selected\"";
										} ?>>預設</option>
					<option value="2" <?php if (!(strcmp(2, $blogInfo['blogframe']))) {
											echo "selected=\"selected\"";
										} ?>>二號</option>
					<option value="3" <?php if (!(strcmp(3, $blogInfo['blogframe']))) {
											echo "selected=\"selected\"";
										} ?>>3(beta)</option>
					<option value="4" <?php if (!(strcmp(4, $blogInfo['blogframe']))) {
											echo "selected=\"selected\"";
										} ?>>4(beta)</option>
					<option value="5" <?php if (!(strcmp(5, $blogInfo['blogframe']))) {
											echo "selected=\"selected\"";
										} ?>>5(dev)</option>

				</select>
			</div>
			<h4>留言系統 (選填)</h4>
			<div style="padding-left:25px;line-height:2em">
				<input type="checkbox" name='comment_system1' value='1' <?= ($blogInfo['comment_system1'] == 1) ? "Checked" : ""; ?>> RealBlog 原生系統<br />
				<input type="checkbox" name='comment_system2' value='1' <?= ($blogInfo['comment_system2'] == 1) ? "Checked" : ""; ?>> Facebook 留言插件
				<input type="checkbox" name='comment_system3' value='1' <?= ($blogInfo['comment_system3'] == 1) ? "Checked" : ""; ?>> Disqus
				<input type="text" name='disqus_name' value='<?= $blogInfo['disqus_name']; ?>' placeholder='輸入你的Disqus Shortname' />
			</div>
			<h4>其他</h4>
			<table cellspacing="15">
				<tr>
					<td align="right" width="25"></td>
					<td align="left">
						Bootstrap Theme CSS位置(選填):
						<input type='text' name='bootstrap_theme' value="<?= $blogInfo['bootstrap_theme']; ?>" />
					</td>
				</tr>
				<tr>
					<td align="right" width="25"></td>
					<td align="left">
						預設文章編碼:

						<select name="default_content_encoding">
							<? foreach (["HTML", "MARKDOWN", "LATEX"] as $enc) { ?>
								<option value="<?= $enc ?>" <?php if ($enc == $blogInfo['displaynum']) {
																echo "selected='selected'";
															} ?>><?= $enc ?></option>
							<? } ?>
						</select>
					</td>
				</tr>
			</table>

			<div>
				<h4>黑名單設置</h4>
				<table cellspacing="15">
					<tr>
						<td align="right" width="25"></td>
						<td align="left">
							禁止以下用戶名留言(用逗號分隔):<br />
							<input type='text' name='blacklist' value="<?= $blogInfo['blacklist']; ?>" />
						</td>
					</tr>

				</table>
			</div>

			<hr />
			<input class='btn btn-primary btn-raised' type="submit" name="Submit" value="提交修改" />



			<input type="hidden" name="MM_update" value="form1">
		</form>
	</div>
</div>
<?php
include_once('templatecode/footer.php');
?>