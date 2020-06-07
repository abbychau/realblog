<?php
include('include/common.php'); 
$id=intval($_GET['id']);

header ("Content-Type:text/xml; charset=utf-8");

if(cacheValid($rsskey.$id,60)){
	$content = cacheGet($rsskey.$id);
	if($content!=""){
		echo $content;
		exit;
	}
}

$pageinfos = dbAr("SELECT * FROM zb_contentpages WHERE ownerid = $id AND isshow = 1 AND password='' ORDER BY id DESC LIMIT 10");
$Parsedown = new ParsedownExtensions();
$Parsedown->setSafeMode(true);
$Parsedown->setAllLinksNewTab(true);

$blogInfo = dbRow("SELECT username, blogname FROM zb_user WHERE id = $id"); 

$strXml="<?xml version=\"1.0\" encoding=\"utf-8\"?>
<rss version='2.0'>
<channel>
<title>RealBlog - Feed - {$blogInfo['blogname']}</title>
<link>http://realblog.zkiz.com/{$blogInfo['username']}</link>
<description>{$blogInfo['blogname']} RSS Feed</description>
<language>zh-tw</language>
<lastBuildDate>".date("r")."</lastBuildDate>";
// echo $pageinfo['content_markup']."XXX";
foreach($pageinfos as $pageinfo) {

	if($pageinfo['content_markup']=='MARKDOWN'){
		$pageinfo['content'] = $Parsedown->text($pageinfo['content']);
	}else{
		$pageinfo['content'] = strip_tags($pageinfo['content'],"<p><br><div>");
	}

$strXml .= "<item>
<title>{$pageinfo['title']}</title>
<link>http://realblog.zkiz.com/{$blogInfo['username']}/{$pageinfo['id']}</link>
<description><![CDATA[{$pageinfo['content']}]]></description>
<pubDate>{$pageinfo['datetime']}</pubDate>
</item>";
}
$strXml .= "</channel></rss>";
echo $strXml;
cacheSet($rsskey.$id,$strXml);