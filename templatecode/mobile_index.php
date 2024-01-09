<style>
	section{padding:5px;background:#EEE;border-radius:1em;margin:1em}
</style>

<section>
	<form method="get" action="http://zkiz.com/tag.php" id="searchform" style='padding-top:3px'>
		
		<input type="text" class="text ui-widget-content ui-corner-all" name="tag" value="ZKIZ.com 搜尋" onclick="this.value=''" style="padding:5px;float:left;height:17px;width:150px; margin-right:1px" />
		
		<a class="button left" onclick="$('#searchform').submit();"><span class="ui-icon ui-icon-search"></span></a>
		
	</form>
	<div class='clear'></div>
</section>
<section>
	<a href="http://ec.zkiz.com" class='button'><img src="http://share.zkiz.com/official/EC.jpg" alt="Endless Choice" border="0" /> EC</a>
	<a href="http://realforum.zkiz.com" class='button'><img src="http://share.zkiz.com/official/RF.jpg" alt="Real Forum" border="0"  /> Forum</a> 
	<a href="http://www.zkiz.com" class='button'><img src="http://share.zkiz.com/official/ZKIZ.jpg" alt="ZKIZ.com" border="0"  /> Home</a> 
</section>


<section>
	<h3>熱門BLOG文</h3>

	<ul>
		<?php foreach ($hot_topics as $row){ ?>
			<li>
				<a href="/<?php echo $row['username']; ?>/<?php echo $row['id']; ?>"><?php echo htmlspecialchars(mb_substr($row['title'],0,40,'UTF-8')); ?></a>
				<span style='color:#AAA'><?php echo timeago(strtotime($row['create_time'])); ?> : </span>
				<span style='color:#AAA'>[<?php echo $row['blogname']; ?>]</span>
				(<?php echo $row['comment_count']; ?>個評論)
			</li>
		<?php } ?>
	</ul>
</section>
<section>
	<h3>熱門BLOG</h3>
	<ul>
		<?foreach($rbmems as $v){?>
			<li><a style='font-weight:bold' href='http://realblog.zkiz.com/<?=$v['username'];?>' title='<?=$v['blogname'];?>'><?=$v['blogname'];?></a><br /><span style='color:#999'><?=htmlspecialchars(mb_substr($v['slogan'],0,80,'UTF-8'));?>...</span></li>
		<?}?>
	</ul>
</section>
<section>
	<h3>最後更新網誌</h3>
	<div>
		<ul>
			<?php 
				foreach ($getRenew as $row_getRenew){
					echo "<li><a href='http://realblog.zkiz.com/".$row_getRenew['username']."'>".$row_getRenew['blogname']."</a></li>";
				}
			?>
		</ul>
	</div>
	
</section>
<section>
	
	<h3>加入RealBlog</h3>
	
	<div >
		免費<strong>無限</strong>儲存空間<br />
		Blog 空間主題CSS<strong>任意自由創作</strong><br />
		<strong>CSS、Javascript 和HTML</strong> 無限制使用<br />
		可以自行加入<strong>廣告</strong><br />
		<a href="http://members.zkiz.com/" class="button" style="margin-top:5px;">建立自己的BLOG 吧</a>
	</div>
	
</section>

<section>
	<h3>有關RealBlog</h3>
	<div>
		<ul>
			<li><a href="http://realforum.zkiz.com/viewforum.php?fid=169" target="_blank">Real Blog 官方討論版</a></li>
			<li><a href="http://wiki.zkiz.com/realblog" target="_blank">Real Blog Wiki Description</a></li>
			<li><a href="/bloglist.php" target="_blank">BLOG 列表</a></li>
		</ul>
	</div>
</section>
<section>

		<h3>熱門Tags</h3>
		<?php foreach($tags as $tag){ ?>
			<a href="http://zkiz.com/tag.php?tag=<?=htmlspecialchars($tag['tag']);?>"><?=$tag['tag'];?></a>
		<?php }?>
		
		
		<?if($fbme){?>
			Connected to Facebook as <br /><a href='<?=$fbme['link'];?>'><strong><?=$fbme['name'];?></strong></a>
			<?}else{?>
			<a href='<?=$facebook->getLoginUrl(array("read_stream","publish_stream"));?>'>
			Connect to Facebook
			</a>
			
		<?}?>
		
		<script type='text/javascript' src='http://xslt.alexa.com/site_stats/js/s/a?url=realblog.zkiz.com'></script>
		<br />
		<a href='http://validator.w3.org/check?uri=referer'><img src='http://www.w3.org/Icons/valid-xhtml10-blue' alt='Valid XHTML 1.0 Transitional' height='31' width='88' /></a>
</section>