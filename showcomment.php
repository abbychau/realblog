<?php 
	require_once('include/common.php'); 
	
	if (intval($_GET['tid'])) {
		$gTid = intval($_GET['tid']);
		}else{
		die("Non specified id.");
	}
	//die("$gTid ..");
	$arrReplies = dbAr("SELECT * FROM zb_comment WHERE pageid = $gTid order by id");
	
	$owner=dbRs("SELECT ownerid FROM zb_contentpages WHERE id = $gTid");
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">評論</h4>
	</div>
	<div class="panel-body">	
		
		<?php if ($arrReplies){ ?>
			<? foreach ($arrReplies as $row_getcomment){	?>
				<div class='commentdiv' id='comment<?=$row_getcomment['id'];?>'>
					<?=++$i;?><? if($row_getcomment['is_whisper'] == "1"){?>(對Blog 主的悄悄話)<?}?>. 
					
					
					<? if($row_getcomment['is_whisper'] == "0" || $gUsername==$blogInfo['username']){?>
						<?php if ($row_getcomment['verified']>0){?> 
							<strong>名稱:</strong><a href="/<?=htmlspecialchars($row_getcomment['name']); ?>"><?=htmlspecialchars($row_getcomment['name']); ?></a>
							<?php if ($row_getcomment['verified']==2){?>
								<strong class='ownerIndicator'>(Blog主)</strong>
								<?}else{?>
								<strong class='memberIndicator'>(RB用戶)</strong>
							<?}?>
							<?php }else{?>
							<strong>名稱:</strong><?=htmlspecialchars($row_getcomment['name']); ?> 
						<? }?>
						
						
						<?php if($row_getcomment['email']!=""){ ?>
							&nbsp;<strong>E-mail:</strong><?=htmlspecialchars($row_getcomment['email']); ?>
						<?php } ?>
						
						<strong>時間:</strong> <?=$row_getcomment['time']; ?>&nbsp;
						<?php if ($blogInfo['username']==$gUsername){?>
							<a onclick="deletecomment('<?=$row_getcomment['id'];?>')" href="javascript:">刪除</a>
						<?php } ?>
						<div>
							<?=nl2br(htmlspecialchars($row_getcomment['content'])); ?>
						</div>
						<?php if($row_getcomment['comment'] != ""){?>
							<? $small_comments=unserialize($row_getcomment['comment']); ?>
							<?php foreach($small_comments as $k=>$comment){ ?>
								<div class='commentdiv' data-small-id="<?=$row_getcomment['id'];?>_<?=$k;?>">
									<strong><?=$comment['username'];?></strong> : <?=htmlspecialchars($comment['content']);?>
									(<?=timeago($comment['timestamp']);?>) <?=$comment['isUser']?"(用戶認證)":"";?>
									<? if($gUsername==$blogInfo['username']){?>
										<a  onclick="deleteReplyReply(<?=$row_getcomment['id'];?>,<?=$k;?>)">刪除</a>
									<? } ?>
								</div>
							<?php } ?>
						<?php } ?>
						
					<? } ?>
					<a onclick='$("#formcomment<?=$row_getcomment['id'];?>").toggle()' class='btn btn-default btn-sm btn-raised'>留言</a>
					
					<? if($blogInfo["login_to_comment"]!=1 || $isLog){ ?>
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
								<input type="checkbox" name="whisper" value="1" /> 悄悄話
								<br />
								<input type="submit" name="button" value="留言" />
								
							</form>
						</div>
					<? } ?>
			<?}?>
					<?php } else {?>
					這篇文章暫時未有評論<br />
				<?php } ?>
			</div>
			<? if($blogInfo["blacklist"]!="" && in_array($gUsername,explode(",",$blogInfo["blacklist"])) ){ ?>Blacklisted<? }else{ ?>
			<div class="commentdiv panel-body">
				<form id="cmt<?=$gTid;?>" name="cmt<?=$gTid;?>" method="post" action="/viewpage.php?tid=<?=$gTid;?>">
					名稱: <input type="text" name="name" value="<?=$gUsername;?>" />
					<br />
					E-mail: <input type="text" name="email" />
					<br />
					評論:<br />
					<textarea name="content" cols="50" rows="5"></textarea>
					<?php if($owner!=$gId){?>
						<script src='https://www.google.com/recaptcha/api.js'></script>
						<div class="g-recaptcha" data-sitekey="6LdZOgkTAAAAAAOpVl8Bfdky9zdmjho7wSURgBnm"></div>
					<?php } ?>
					<br />
					<input type="checkbox" name="is_whisper" value="1" /> 悄悄話
					<input type="submit" name="Submit" value="送出" />
					<input type="hidden" name="reply_blog" value="1">
				</form>
			</div>
			<?}?>
		</div>		