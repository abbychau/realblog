<?php 
include_once("../include/common.php");

dbQuery("update `zb_contentpages` a set `comment_count` = (select count(*) from zb_comment as b WHERE a.id = b.pageid)");
dbQuery("delete from zb_comment WHERE pageid not in (SELECT id from zb_contentpages)");
