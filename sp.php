<?php
require_once('Connections/zkizblog.php'); 
$i = intval($_GET['i']);
//for($j = $i ; $j <= $i+10; $j++){

$from = $_GET['from']==""?1:$_GET['from'];
$to = $_GET['to']==""?100:$_GET['to'];
$hdl = mysql_query("SELECT id, content FROM zb_contentpages WHERE id BETWEEN $from AND $to");

while($row = mysql_fetch_assoc($hdl)){
	mysql_query("UPDATE zb_contentpages SET content = ".GetSQLValueString(strip_tags($row['content'],"<tr><td><table><img><br><a><p><b><i><strong><u><span><object>"), "text")." WHERE id = ".$row['id']);
}
$from = $from+100;
$to = $to+100;
//header("location:");
?>
<script>
setTimeout("location.href='sp.php?from=<?=$from;?>&to=<?=$to;?>'",1000);
</script>