<? if($gTag!=""){?>
	<h3 >TAG: <?=$gTag;?></h3>
<?}?>
<? foreach($contentList as $v) { ?>
	
	<h2 class="articletitle"><a href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>"><?=$v['title']; ?></a></h2>
	
	<? $v['display_mode']= ($v['display_mode']==0)?$blogInfo['displaytype']:$v['display_mode'];?>
	<? if($v['display_mode']==1){?>
		<div class="contentbody">
			<? if($v['password']){?>
				Password Protected.
				<form action="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>" method="post">
					<input type="password" name='password' />
					<input type='submit' value='Enter' />
				</form>
				<?}else{?>
				
				<?=$v['content'];?>
			<?}?>
		</div>
	<? } ?>
	
	<? if($v['display_mode']==2){?>
		<!-- TITLE ONLY -->
	<?}?>
	
	
	<? if($v['display_mode']==3 || $v['display_mode']==2){?>
		<div class="contentbody abstract">
			
			
			<? if($v['password']){?>
				Password Protected.
				<form action="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>" method="post">
					<input type="password" name='password' />
					<input type='submit' value='Enter' />
				</form>
				<?}else{?>
				
				<? preg_match_all('/<img[^>]+>/i',$v['content'], $imgTags);?>
				<?if($imgTags[0][0]){?>
					<?=$imgTags[0][0];?><br />
				<?}?>
				<?=mb_substr(strip_tags($v['content']),0,300,'utf-8');?>
				<div style='display:block;margin:1em 0'>				
					<a class="btn btn-primary" href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>">閱讀全文 <span class="glyphicon glyphicon-chevron-right"></span></a>
				</div>
			</div>
		<?}?>
	<?}?>
	
	<? if($v['display_mode']==4){?>
		
		<div class="contentbody abstract">
			<?
				@$dom->loadHTML($v['content']);
			$images = @$dom->getElementsByTagName('img');?>
			<div class='image_list'>
				<?foreach ($images as $image) {?>
					<a href='/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>'><img src="<?=$image->getAttribute('src');?>" style='width:45%;max-width:150px' /></a>
				<?}?>
			</div>
			<a href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>">觀看大圖</a>
		</div>
	<?}?>
	
	
	<span class="glyphicon glyphicon-time"></span>發表時間：<?=timeago(strtotime($v['create_time'])); ?>
	<? if($gUsername==$blogInfo['username']){?>
		| <a href="/compose.php?tid=<?=$v['id']; ?>">修改</a> | <a href="/delete.php?tid=<?=$v['id']; ?>">刪除</a>
	<? } ?>
	<? if($blogInfo['comment_system1'] == 1){?>
		| <a onclick="$('#showcomment<?=$v['id']; ?>').toggle('slow').load('/showcomment.php?tid=<?=$v['id']; ?>');">評論 (<?=$v['comment_count']; ?>)</a>
	<? }?>
	<? if($blogInfo['comment_system2'] == 1){?>
		| <a href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>">評論(<fb:comments-count href="http://realblog.zkiz.com/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>"></fb:comments-count>)</a>
	<?}?>
	<? if($blogInfo['comment_system3'] == 1){?>
		| <a href="http://realblog.zkiz.com/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>#disqus_thread" data-disqus-identifier="rb/<?=$v['id']; ?>">評論</a>
	<? } ?>
	| <a href="/<?=$blogInfo['username']; ?>/<?=$v['id']; ?>">全文</a>
	<div id="showcomment<?=$v['id']; ?>" style='display:none'><img src="/images/loading.gif" alt="loading..." />&nbsp;</div>
	
	<hr>
<? } ?>

<ul class="pagination">
	<li <? if ($curPageNo == 0) { ?>class="disabled"<?}?>><a href="/<?=$blogInfo['username']."/".$temptype; ?>/0">&laquo;</a></li>
	
	<? 
			for($i=max(0,$curPageNo-5);$i <= min($curPageNo + 5,$contentListCount - 1); $i++){
				if($i==$curPageNo){
				?>
				<li class="active"><a href="#"><?=$i+1;?><span class="sr-only">(current)</span></a></li>
				<? }else{?>
				<li><a href=<?="/".$blogInfo['username']."/".$temptype."/$i";?>><?=$i + 1;?></a></li>
				<?}		
			}
	?>
	<li><a href="/<?=$blogInfo['username']."/".$temptype."/".($curPageNo + 1); ?>">&raquo;</a></li>
</ul>

<? if($blogInfo['comment_system3'] == 1 && trim($blogInfo['disqus_name'])!=""){?>
	<script id="dsq-count-scr" src="//<?=$blogInfo['disqus_name'];?>.disqus.com/count.js" async></script>
<?}?>