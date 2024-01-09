<div class='row'>
    <div class='col-xs-12 col-md-9'>



        <div class="panel panel-default">
            <div class="panel-body">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- rb_responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4549800596928715"
                     data-ad-slot="2362568181"
                     data-ad-format="auto"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
            <div class="panel-footer">
                廣告
            </div>
        </div>


<?php
	$query_getBloglist = "
	SELECT username, blogname, title, b.id, create_time FROM
	(SELECT id, username, blogname,blacklisted FROM zb_user) a,
	(SELECT b1.id , b1.user_id, title, create_time FROM zb_contentpages b1, (SELECT user_id, max(id) as id FROM zb_contentpages group by user_id) b2 WHERE b1.id = b2.id AND b1.user_id = b2.user_id) b
	WHERE a.id = b.user_id
	AND a.blacklisted <> 1
	ORDER BY b.id DESC LIMIT 50
	";
	$getBloglist = dbAr($query_getBloglist);

?>
<table>
	<tr style="font-weight:bold;">
		<td width="200" >Blog</td><td>最後主題</td>
	</tr>
	<tr>
	<td style="border-bottom:1px #DDD solid" colspan="4">
	
	</td>
	<?php foreach($getBloglist as $row_getBloglist){ ?>
		<tr>
			<td><a href="/<?php echo $row_getBloglist['username']; ?>"><?=mb_substr($row_getBloglist['blogname'],0,40,"utf8"); ?></a></td>
			<td><a href="/<?php echo $row_getBloglist['username']; ?>/<?=$row_getBloglist['id'];?>"><?=mb_substr($row_getBloglist['title'],0,40,"utf8");?></a></td>
		</tr>
	<?php } ?>
</table>
    </div>
    <div class='col-xs-12 col-md-3'>
        <? if (!$isLog) { ?>
            <section>

                <h3>加入RealBlog</h3>

                <div>
                    <ul>
                        <li>免費<strong>無限</strong>儲存空間</li>
                        <li>Blog 空間主題CSS<strong>任意自由創作</strong></li>
                        <li><strong>CSS、Javascript 和HTML</strong> 無限制使用</li>
                        <li>可以自行加入<strong>廣告</strong></li>
                        <li><a style='font-weight:bold' href='//members.zkiz.com/reg.php'>馬上建立自己的BLOG 吧!</a></li>
                    </ul>
                </div>


            </section>
        <? } ?>
        <style scoped>
            .list-group .list-group-item {
                border: 1px solid #eee;
                padding: .5em 1em;
            }
        </style>
        <!-- <div class='panel panel-default'>
            <div class='panel-heading'>
                <h4 class='panel-title'>熱門Blog 文</h4>
            </div>
            <ul class="list-group" id="hot_blog_post">
                <?php foreach ($hot_topics as $row) { ?>
                    <li class="list-group-item">
                        <span class="badge">CM: <?= $row['comment_count']; ?></span>
                        <span class='c999'><?php echo timeago(strtotime($row['create_time'])); ?> : </span>
                        <a href='/<?= $row['username']; ?>' style="color:#666"><?php echo $row['blogname']; ?></a>
                        <br/>
                        <a href="/<?php echo $row['username']; ?>/<?php echo $row['id']; ?>"
                           class='list-title'><?php echo htmlspecialchars(mb_substr($row['title'], 0, 40, 'UTF-8')); ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div> -->
        <a onclick="dialog($('#hot_blog_post'),'熱門Blog 文')" style="position:fixed;right:1em;bottom:1em" class="btn btn-primary btn-fab hidden-lg hidden-md">

            <i class="material-icons">grade</i><div class="ripple-container"></div>

        </a>
        <!--
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<h4 class='panel-title'>最後加入的文章</h4>
			</div>
			<ul class="list-group">
				<?php foreach ($getentries as $row) { ?>
					<li class="list-group-item">
						<span class="badge">CM: <?= $row['comment_count']; ?></span>
						<span class='c999'><?php echo timeago(strtotime($row['create_time'])); ?> : </span>
						<a href='/<?= $row['username']; ?>' style="color:#666"><?php echo $row['blogname']; ?></a>
						<br />
						<a href="/<?= $row['username']; ?>/<?= $row['id']; ?>" class='list-title'><?= htmlspecialchars(mb_substr($row['title'], 0, 40, 'UTF-8')); ?></a>
					</li>
				<?php } ?>
			</ul>
		</div>
		-->
<!-- 

        <div class='panel panel-default' style='padding:0.2em'>
            <div class="fb-page" data-href="https://www.facebook.com/zkizcom/" data-tabs="timeline"
                 data-small-header="false" data-adapt-container-width="true" data-hide-cover="false"
                 data-show-facepile="true">
                <div class="fb-xfbml-parse-ignore">
                    <blockquote cite="https://www.facebook.com/zkizcom/"><a href="https://www.facebook.com/zkizcom/">zkiz.com</a>
                    </blockquote>
                </div>
            </div>
        </div> -->

<!-- 
        <div class='panel panel-default'>
            <div class='panel-heading'>
                <h4 class='panel-title'>最後更新網誌</h4>
            </div>
            <div class='panel-body' style='font-size:1.2em'>
                <?php
                foreach ($getRenew as $row_getRenew) {
                    echo "<li><a href='//realblog.zkiz.com/" . $row_getRenew['username'] . "'>" . $row_getRenew['blogname'] . "</a></li>";
                }
                ?>
            </div>
        </div> -->

<!-- 
        <div class='panel panel-default'>
            <div class='panel-heading'>
                <h4 class='panel-title'>最受歡迎博客</h4>
            </div>
            <ul class="list-group">
                <?php foreach ($rbmems as $v) { ?>
                    <li class="list-group-item">
                        <a href='//realblog.zkiz.com/<?= $v['username']; ?>' class='list-title'
                           title='<?= $v['blogname']; ?>'><?= $v['blogname']; ?></a><br/>
                        <span><?= htmlspecialchars(mb_substr($v['slogan'], 0, 80, 'UTF-8')); ?>...</span>
                    </li>
                <?php } ?>
            </ul>
        </div> -->

        <div class='panel panel-default'>
            <div class='panel-heading'>
                <h4 class='panel-title'>熱門Tags</h4>
            </div>
            <div class='panel-body' style='font-size:1.2em'>
                <?php foreach ($tags as $tag) { ?>
                    <a class='badge'
                       href="//zkiz.com/tag.php?tag=<?= htmlspecialchars($tag['tag']); ?>"><?= $tag['tag']; ?></a>
                <?php } ?>
                <hr />
                <a href='https://readme.zkiz.com' target="_blank">Code Documentations</a>
            </div>
        </div>


        <div class='panel panel-default'>
            <div class='panel-body' style='font-size:1.2em'>

                <a href="/bloglist.php" target="_blank">BLOG 列表</a><br />
                <a href="https://github.com/abbychau/realblog/issues">回報問題</a>
                
            </div>
        </div>

    </div>
</div>


<div class='clear'></div>