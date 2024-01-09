<h1 class="blogtitle"><a href="/<?=$blogInfo['username']; ?>"><?=$blogInfo['blogname'] ?></a></h1>
<?php if ($blogInfo['blogname']!=""){ ?>
	<div class="slogan"><?=$blogInfo['blogname'];?></div>
<?php }?>
<?php if ($blogInfo['topbar']!=""){ ?>
	<?=$blogInfo['topbar'];?>
<?php } ?>

<div class="mainpart">
	<div class="cate">
		
		<?php foreach($getTypes as $row_gettype) { ?>
			<span class="cateitem <?=($gType == $row_gettype['id'])?"selected":"";?>">
				<a href="/<?=$blogInfo['username']; ?>/<?=$row_gettype['id']; ?>/0"><?=$row_gettype['name']; ?></a>(<?=$row_gettype['ce']; ?>)
			</span>
		<?php } ?>
		
	</div>
	
	<div class="clear"></div>
	<div class="artNav">
		<div class="left">
			&lsaquo; <a href='/<?=$blogInfo['username']; ?>/<?=$prevArticle['id'];?>'><?=$prevArticle['title'];?></a>
		</div>
		
		<div class="right">　　
			<a href='/<?=$blogInfo['username']; ?>/<?=$nextArticle['id'];?>'><?=$nextArticle['title'];?></a>&rsaquo;
		</div>
		<div class="clear"></div>
	</div>
	<h4 class="articletitle"><?php echo "[".$thisType."]".$row_getpage['title']; ?>
		<?php if($gUsername==$blogInfo['username']){?>
			<span style="font-size:12px; font-weight:normal"><a href="/compose.php?tid=<?php echo $row_getpage['id']; ?>">修改</a>&nbsp;<a href="/delete.php?tid=<?php echo $row_getpage['id']; ?>">刪除</a></span>
		<?php } ?>
	</h4>
	@ <?php echo $row_getpage['create_time']; ?><br />
	
	<script type='text/javascript'>
		function deletecomment(id){
			if (window.confirm('你確定嗎?')==true){
				$.post(
				"<?php echo $editFormAction; ?>",
				{cid: id},
				function(data){
					if(data == 'success'){$('#comment' + id).hide('slow');}
				}
				);
			}
			
		}
		
	</script>
	
	<div class="contentbody" id="pagecontent">
		<?if ($row_getpage['is_show']==1){?>
			
			<?php 
				
				echo preg_replace('/\[\[(.*?)\]\]/is', '<a href="http://realforum.zkiz.com/thread.php?wikiterm=$1">$1</a>', $row_getpage['content']);
				
			?>
			
			<div class="tagsinput">
				
				<?foreach($thistags as $v){?>
					
					<a class="tagview" href="/<?=$gUser;?>/tag/<?=$v; ?>">
						<?=$v; ?>
					</a>
				<?}?>
			</div>
			
			<?}else{?>
			此文章已被隱藏
		<?}?>
	</div>
	<h4>評論</h4><a name="commenta" id="commenta"></a>
	<form name="delcom" id='delcom' method="post" action="<?php echo $editFormAction; ?>">
	<input name="cid" type="hidden" id='cid' value="" /></form>
	<?php 
		if ($totalRows_getcomment>0){  
			foreach ($getcomment as $row_getcomment) {
				$i++;
			?>
			<div class='commentdiv' id='comment<?php echo $row_getcomment['id'];?>'>
				<?=$i;?>. 
				<?php if ($row_getcomment['verified']>0){?> 
					<strong>名稱:</strong><a href="/<?php echo htmlspecialchars($row_getcomment['name']); ?>"><?php echo htmlspecialchars($row_getcomment['name']); ?></a>
					<?php if ($row_getcomment['verified']==2){?> <strong style="color:#663">(Blog主)</strong><?}else{?> <strong style="color:#930">(RB用戶)</strong><?}?>
					<?php }else{?>
					<strong>名稱:</strong><?php echo htmlspecialchars($row_getcomment['name']); ?> 
				<? }?>
				
				
				<?php if($row_getcomment['email']!=""){ ?>
					&nbsp;<span style="font-weight: bold">E-mail:</span><?php echo htmlspecialchars($row_getcomment['email']); ?>
				<?php } ?>
				
				<span style="font-weight: bold">時間:</span> <?php echo $row_getcomment['time']; ?>&nbsp;
				<?php if ($blogInfo['username']==$gUsername){?>
					<a onclick="deletecomment('<?php echo $row_getcomment['id'];?>')" href="javascript:">刪除</a>
				<?php } ?>
				<br />
				<?php echo nl2br(htmlspecialchars($row_getcomment['content'])); ?><br />
				<div id="rr<?=$i;?>" style="display:none; padding-left:10px"></div>
			</div>
		<?php }  } else {?>
		<div class='commentdiv'>
			這篇文章暫時未有評論
		</div>
	<?php } ?>
	<div id='fbcommentbox'></div>
	<br />
	<div class="commentdiv">
		<strong>留言</strong><br />
		<?php if($error == 1){?>
			<span style='color:#FF0000; font-weight:bold'>驗證碼錯誤, 請重新輸入</span>
		<?php } ?>
		<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
			名稱: <input type="text" name="name" value="<?=$gUsername;?>" />
			E-mail: <input type="text" name="email" />
			<br />
			評論:<br /><textarea name="content" cols="40" rows="5"><?=$failtext;?></textarea>
			<?php if($owner!=$gId){?>
				<br />
				<img src="/include/securimage/securimage_show.php" alt="CAPTCHA Image" /><br />
				<div id="recaptcha_image"></div>
				<input type="text" name="recaptcha_response_field" id="recaptcha_response_field" value="" onClick="this.value=''" onBlur="if(this.value==''){this.value='請輸入以上的認證碼';}" style="color:#999" size="40" />
			<?php }?>
			<br />
			<input type="submit" name="Submit" value="送出" />
			<input type="hidden" name="MM_insert" value="form1">
			<input type="hidden" name="MM_update" value="form1">
		</form>
	</div>
</div>
<script type="text/javascript">
	document.getElementById('recaptcha_response_field').value="請輸入以上的認證碼";
</script>