
<ul class="pager">
    <li class="previous"><a href='/<?=$blogInfo['username']; ?>/<?=$gTid;?>/prev'>← 上一篇</a></li>
    <li class="next"><a class="withripple" href='/<?=$blogInfo['username']; ?>/<?=$gTid;?>/next'>下一篇 →</a></li>
</ul>
<!-- writing-mode: vertical-rl; -->
<div class="content_container">
	
	
	<div class='custom_display'>
		
		<? if($row_getpage['type']!=-1){?>
			<h1 class="articletitle"
			style='display:inline'
			><?=$row_getpage['title']; ?> </h1>
		<?}?>
		
		<div style='display:inline'	>
			<span class="glyphicon glyphicon-time"></span> <?=$row_getpage['datetime']; ?>
			
			<div class="contentbody" id="pagecontent">
				<?if ($row_getpage['isshow']==1){?>
					
					<? if($row_getpage['password'] && ($_POST['password'] != $row_getpage['password'])){?>
						Password Protected.
						<form action="" method="post">
							<input type="password" name='password' />
							<input type='submit' value='Enter' />
						</form>
						<?}else{?>
						<?php 
							
							if($row_getpage['content_markup']=='MARKDOWN'){
								$Parsedown = new ParsedownExtensions();
								$Parsedown->setSafeMode(true);
								$Parsedown->setAllLinksNewTab(true);
								echo $Parsedown->text($row_getpage['content']);
								}else{
								echo $row_getpage['content'];
							}
							
							
						?>
					<?}?>
					<?}else{?>
					此文章已被隱藏
				<?}?>
				
				<div style='display:block;margin-top:2em' class="fb-share-button" data-href="http://realblog.zkiz.com/<?=$blogInfo['username']; ?>/<?=$gTid;?>" data-layout="button_count"></div>
				<div class='clear'></div>
			</div>
		</div>
		
		<span style='opacity:0.5'>
			
			<span class="glyphicon glyphicon-th-list"></span> <a href='/<?=$blogInfo['username']?>/<?=$row_getpage["type"];?>/0'><?=$thisType?></a>
			<div style='display: inline; padding:0 1em'>
				<?if($thistags && $row_getpage['isshow']==1){?>
					<span class="glyphicon glyphicon-tag"></span> 
					<?foreach($thistags as $v){?>
						<a class='' href="/<?=$gUser;?>/tag/<?=$v; ?>"><?=$v; ?></a> 
					<?}?>
				<?}?>
			</div>
			
			
			<?php if($gUsername==$blogInfo['username']){?>
				<a href="/compose.php?tid=<?=$row_getpage['id']; ?>">修改</a>
				| <a href="/delete.php?tid=<?=$row_getpage['id']; ?>">刪除</a>
			<?php } ?>
			<span style='padding-left:1em'>
	<a onclick='toSlate()'>暗</a>
	| <a onclick='toBright()'>亮</a>
	</span>
	<script>
		function toSlate(){
			$('#replacable_bootstrap_css').attr("href","https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.5/slate/bootstrap.min.css");
		}
		function toBright(){
			$('#replacable_bootstrap_css').attr("href","");
		}
	</script>

		</span>
	</div>
</div>


<div class='commenter_container'>
	<? if($blogInfo['comment_system1'] == 1){?>
		
		<? if($blogInfo["login_to_comment"]!=1 || $isLog){?>
			
			<? if($blogInfo["blacklist"]!="" && in_array($gUsername,explode(",",$blogInfo["blacklist"])) ){ ?>Blacklisted<? }else{ ?>
				<div class="well">
					
					<h4>留言</h4>
					<a name="commenta" id="commenta"></a>
					<?php if($error == 1){?>
						<div style='color:#FF0000; font-weight:bold'>驗證失敗, 請再試一次</div>
					<?php } ?>
					<div>
						<form id="form1" method="POST" action="<?=$editFormAction; ?>">
							
							<style>.comment_text{display:block;height:250px}</style>
							<textarea class='form-control' placeholder="留言" name="content" onfocus='$("#type_to_show").show("slow");' onkeyup='$("#type_to_show").show("slow");' onblur='if($("#type_to_show").val().length==0){$("#type_to_show").hide("slow");}' required><?=$failtext;?></textarea>
							
							<div id='type_to_show' class='myhide'>
								<? if($isLog){?>
									<input type="hidden" placeholder='名稱' name="name" value="<?=$gUsername;?>"  />
									Logged in as <em><?=$gUsername;?></em>.
									<?}else{?>
									<div class="row">
										<div class="form-group col-xs-6">
											<input class="form-control" type="text" placeholder='名稱' name="name" value="<?=$gUsername;?>" required />
										</div>
										<div class="form-group col-xs-6">
											<input class="form-control" type="email" placeholder="E-mail" name="email" required />
										</div>
									</div>
								<?}?>
								
								
								<?php if($owner!=$gId){ ?>
									<script src='https://www.google.com/recaptcha/api.js'></script>
									<div class="g-recaptcha" data-sitekey="6LdZOgkTAAAAAAOpVl8Bfdky9zdmjho7wSURgBnm"></div>
								<?php } ?>
								<input type="checkbox" name="is_whisper" value="1" /> 悄悄話<br />
								<input type="submit" name="Submit" value="送出" class='btn btn-primary btn-raised' />
								<input type="hidden" name="reply_blog" value="1">
							</div>
							
							
						</form>
					</div>
				</div>
			<?}?>
			<?}else{?>
			<div class="well">
				本博客設定了必須登入後才可以留言。
			</div>
		<?}?>
		<hr />
	</div>
	<div class='comment_container'>
		<form name="delcom" id='delcom' method="post" action="<?=$editFormAction; ?>">
		<input name="cid" type="hidden" id='cid' value="" /></form>
		<? foreach ($comments as $row_getcomment){	?>
			<div class='commentdiv' id='comment<?=$row_getcomment['id'];?>'>
				<?=++$i;?><? if($row_getcomment['is_whisper'] == "1"){?>(對Blog 主的悄悄話)<?}?>. 
				
				
				<? if($row_getcomment['is_whisper'] == "0" || $gUsername==$blogInfo['username'] || ($row_getcomment['verified']==2 && $row_getcomment['name'] == $gUsername)){?>
					<?php if ($row_getcomment['verified']>0){?> 
						<strong>名稱:</strong><a href="/<?=htmlspecialchars($row_getcomment['name']); ?>"><?=htmlspecialchars($row_getcomment['name']); ?></a>
						<?php if ($row_getcomment['verified']==2){?>
							<strong class='ownerIndicator'>(Blog主)</strong>
							<?}else{?>
							<strong class='memberIndicator'>(RB用戶)</strong>
						<?}?>
						<?php }else{?>
						<strong>名稱:</strong><?=htmlspecialchars_decode($row_getcomment['name']); ?> 
					<? }?>
					
					
					<?php if($row_getcomment['email']!=""){ ?>
						&nbsp;<strong>E-mail:</strong><?=htmlspecialchars($row_getcomment['email']); ?>
					<?php } ?>
					
					<strong>時間:</strong> <?=$row_getcomment['time']; ?>&nbsp;
					<?php if ($blogInfo['username']==$gUsername){?>
						<a onclick="deletecomment('<?=$row_getcomment['id'];?>')" href="javascript:">刪除</a>
					<?php } ?>
					<div>
						<?=nl2br(htmlspecialchars_decode($row_getcomment['content'])); ?>
					</div>
					<?php if($row_getcomment['comment'] != ""){$small_comments=unserialize($row_getcomment['comment']);
						
					?>
					<? if(is_array($small_comments)){?>
						<?php foreach($small_comments as $k=>$comment){?>
							<div class='commentdiv' data-small-id="<?=$row_getcomment['id'];?>_<?=$k;?>">
								<strong><?=$comment['username'];?></strong> : <?=htmlspecialchars_decode($comment['content']);?>
								(<?=timeago($comment['timestamp']);?>) <?=$comment['isUser']?"(用戶認證)":"";?>
								<? if($gUsername==$blogInfo['username']){?>
									<a  onclick="deleteReplyReply(<?=$row_getcomment['id'];?>,<?=$k;?>)">刪除</a>
								<? } ?>
							</div>
						<?php } ?>
					<?php } ?>
					<?php } ?>
					
				<?}?>
				<? if($blogInfo["blacklist"]!="" && in_array($gUsername,explode(",",$blogInfo["blacklist"])) ){ }else{ ?>
					<a onclick='$("#formcomment<?=$row_getcomment['id'];?>").toggle()' class='btn btn-default btn-sm btn-raised'>回覆</a>
				<?}?>
				<? if($blogInfo["login_to_comment"]!=1 || $isLog){?>
					<div id='formcomment<?=$row_getcomment['id'];?>' class='myhide well' >
						<form name="form2" method="POST" action="<?=$editFormAction; ?>">
							<? if($isLog){?>
								<input type="hidden" name="username" value="<?=$gUsername;?>" />
								Logged in as <em><?=$gUsername;?></em>.
								<?}else{?>
								<input type="text" name="username" value="<?=$gUsername;?>" />
							<?}?>
							<input type="text" name="says" style="width:200px" maxlength="1000" />
							
							<input name="id" type="hidden" value="<?=$row_getcomment['id']; ?>">
							<input name="tid" type="hidden" value="<?=$gTid; ?>" />
							<input type="hidden" name="reply_reply" value="1" />
							<input type="checkbox" name="is_whisper" value="1" /> 悄悄話
							<br />
							<input type="submit" name="button" value="留言" />
							
						</form>
					</div>
				<? } ?>
				
			</div>
			<hr />
		<?php } ?>
		
		<?php if(!$comments){?>
			<div class='commentdiv'>
				這篇文章暫時未有留言
			</div>
		<?php } ?>
	</div>
	
<?}?>
<? if($blogInfo['comment_system2'] == 1){?>
	<div class="fb-comments" data-href="http://realblog.zkiz.com/<?=$editFormAction;?>" data-width="100%"></div>
<?}?>
<? if($blogInfo['comment_system3'] == 1 && trim($blogInfo['disqus_name'])!=""){?>
	<div id="disqus_thread"></div>
	<script>
		/**
			* RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
			* LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
			*/
			var disqus_config = function () {
				this.page.url = 'http://realblog.zkiz.com/<?=$editFormAction;?>'; // Replace PAGE_URL with your page's canonical URL variable
				this.page.identifier = 'rb/<?=$gTid;?>'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
			};
			(function() { // DON'T EDIT BELOW THIS LINE
				var d = document, s = d.createElement('script');
				
				s.src = '//<?=$blogInfo['disqus_name'];?>.disqus.com/embed.js';
				
				s.setAttribute('data-timestamp', +new Date());
				(d.head || d.body).appendChild(s);
			})();
		</script>
		<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
		
<?}?>
	
	

	
	
	<script type='text/javascript'>

		function deletecomment(id){
			if (window.confirm('你確定嗎?')==true){
				$.post(
				"<?=$editFormAction; ?>",
				{cid: id},
				function(data){
					if(data == 'success'){$('#comment' + id).hide('slow');}
				}
				);
			}
			
		}
		function deleteReplyReply(id,k){
			if (window.confirm('你確定嗎?')==true){
				$.post(
                "<?=$editFormAction; ?>",
                {key: k, pcid: id},
                function(data){
                    if(data == 'success'){$('[data-small-id="'+id+"_"+k+'"]').hide('slow');}
				}
				);
			}
			
		}
		
	</script>
