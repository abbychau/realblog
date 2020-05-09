<?php 
include_once("../Connections/zkizblog.php");

mysql_query("update `zb_contentpages` a set `commentnum` = (select count(*) from zb_comment as b WHERE a.id = b.pageid)");
echo "Updated: Reply Number of Threads.  Rows:".mysql_affected_rows()."<br/>";

mysql_query("delete from zb_comment WHERE pageid not in (SELECT id from zb_contentpages)");
echo "Updated: del comments.  Rows:".mysql_affected_rows()."<br/>";

?>