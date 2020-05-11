<div class='row'>
    <div class='col-xs-12 col-md-8'>



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


        <script>
            var lastLoadedId = 0;
            var isLoading = true;
            var myWP;
            function loadDataToTimelineCard(data) {
                $.each(data, function (i, element) {
                    if (element.blogname != null) {
                        var $dom = $("#timeline_card").clone();
                        $dom.attr("id", "timeline_card_" + element.entry_id);
                        $dom.addClass("timeline_card");
                        $dom.find(".title").attr("href", "/" + element.owner_name + "/" + element.entry_id);
                        $dom.find(".title").html(element.title);
                        $dom.find(".datetime").html(element.datetime);
                        $dom.find(".owner_name").html(element.blogname);
                        $dom.find(".owner_name").attr("href", "/" + element.owner_name);
                        $dom.find(".content").html(element.content);
                        $dom.show();
                        $("#timeline").append($dom);
                    }
                    lastLoadedId = element.entry_id;
                });
                $("#loading_icon").hide();
                isLoading = false;
            }
            $(document).ready(function () {
                isLoading = true;
                $("#loading_icon").show();
                $.getJSON("ajaxdata.php", {action: "newest_entry"}, function (data) {
                    loadDataToTimelineCard(data);
                });

            });
            $(window).scroll(function () {
                if ($(window).scrollTop() > $("#timeline").height() - 500) {
                    if (!isLoading) {
                        $("#loading_icon").show();
                        isLoading = true;
                        $.getJSON("ajaxdata.php", {"action": "newest_entry", "last_id": lastLoadedId}, function (data) {
                            loadDataToTimelineCard(data);
                        });
                    }
                }
            });
        </script>
        <div id="timeline_card" class="panel panel-default" style="display:none">
            <div class="panel-body">
                <h4><a class="title"></a></h4>
                <div class="content" style="word-break: break-all"></div>
            </div>
            <div class="panel-footer">
                <span class='datetime'></span> | <a href="/" class="owner_name"></a>

            </div>
        </div>
        <div id='timeline'></div>
        <div id="loading_icon" style="display:none"><i class="fa fa-spinner fa-spin"></i> Loading...</div>
        <div id="wp_bottom"></div>


    </div>
    <div class='col-xs-12 col-md-4'>
        <? if (!$isLog) { ?>
            <section>

                <h3>加入RealBlog</h3>

                <div>
                    <ul>
                        <li>免費<strong>無限</strong>儲存空間</li>
                        <li>Blog 空間主題CSS<strong>任意自由創作</strong></li>
                        <li><strong>CSS、Javascript 和HTML</strong> 無限制使用</li>
                        <li>可以自行加入<strong>廣告</strong></li>

                    </ul>
                    <a href="//members.zkiz.com/reg.php" class="btn btn-default">建立自己的BLOG 吧</a>
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
                        <span class="badge">CM: <?= $row['commentnum']; ?></span>
                        <span class='c999'><?php echo timeago(strtotime($row['datetime'])); ?> : </span>
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
						<span class="badge">CM: <?= $row['commentnum']; ?></span>
						<span class='c999'><?php echo timeago(strtotime($row['datetime'])); ?> : </span>
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
        </div>

        <div class='panel panel-default'>
            <div class='panel-heading'>
                <h4 class='panel-title'>有關RealBlog</h4>
            </div>
            <div class='panel-body' style='font-size:1.2em'>
                <ul>
                    <li><a href="//realforum.zkiz.com/viewforum.php?fid=169" target="_blank">Real Blog 官方討論版</a>
                    </li>
                    <li><a href="//wiki.zkiz.com/realblog" target="_blank">Real Blog Wiki Description</a></li>
                    <li><a href="/bloglist.php" target="_blank">BLOG 列表</a></li>
                </ul>
            </div>
        </div>


        <div class='panel panel-default'>
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
        </div>

        <div class='panel panel-default'>
            <div class='panel-heading'>
                <h4 class='panel-title'>熱門Tags</h4>
            </div>
            <div class='panel-body' style='font-size:1.2em'>
                <?php foreach ($tags as $tag) { ?>
                    <a class='badge'
                       href="//zkiz.com/tag.php?tag=<?= htmlspecialchars($tag['tag']); ?>"><?= $tag['tag']; ?></a>
                <?php } ?>
            </div>
        </div>


        <div class='panel panel-default'>
            <div class='panel-body' style='font-size:1.2em'>
                <div>

                    <a target="_blank" href="//feeds.feedburner.com/~r/zkiz/rb/~6/1">
                        <img src="//feeds.feedburner.com/zkiz/rb.1.gif" alt="Real Blog 最新文章" style="border:0"/></a>
                </div>
                <br/>
                <a href='/activate.php'>重新連結RB 到ZKIZ passport</a>
            </div>
        </div>

    </div>
</div>


<div class='clear'></div>