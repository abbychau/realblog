<?php 
require_once('Connections/zkizblog.php');

header("Content-type: application/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<rss version='2.0'>
  <channel>
    <title>Real Blog 最新文章</title>
    <link>http://realblog.zkiz.com</link>
    <description>Real Blog RSS Feed</description>

   	<lastBuildDate>".date("r")."</lastBuildDate>";
	
	//START ITEMS
	
	$query_getentries = "SELECT a.id, a.title,a.content, a.datetime, a.commentnum, b.username, b.blogname FROM zb_contentpages a, zb_user b where b.blacklisted=0 AND slogan IS NOT NULL AND isshow = 1 AND a.password = '' AND a.ownerid = b.id AND a.ownerid != 423 order by id desc LIMIT 60";
	$entries = dbAr($query_getentries,[], 3600*5);
		
	foreach ($entries as $row_getentries){
		echo "<item>\n";
		echo "<title>[".$row_getentries['blogname']."]".$row_getentries['title']."</title>\n";
		echo "<link>"."http://realblog.zkiz.com/".$row_getentries['username']."/".$row_getentries['id']."</link>\n";
		echo "<pubDate>".date(DATE_RSS,strtotime($row_getentries['datetime']))."</pubDate>\n";
		echo "<description>
		<![CDATA[
		".strip_tags($row_getentries['content'],"<br><p>")."
		]]>
		</description>\n";
		echo "</item>\n";
	}
		
echo "</channel>";
echo "</rss>";