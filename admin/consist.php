<?php 
include_once("../Connections/zkizblog.php");

dbQuery("update `zb_contentpages` a set `commentnum` = (select count(*) from zb_comment as b WHERE a.id = b.pageid)");
dbQuery("delete from zb_comment WHERE pageid not in (SELECT id from zb_contentpages)");
