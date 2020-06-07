<?php 
	require_once('include/common.php'); 
	
	//params
	$gType = intval($_GET['type']);
	$gPageNo = intval($_GET['pageNum_viewconlist']);
	
	if (isset($_GET['username'])) {
		$gZid = dbRs("SELECT id FROM zb_user WHERE username = :username",['username'=>$_GET['username']]);
		if(!$gZid){header("location:http://realblog.zkiz.com");exit;}
	}else{
		$gZid = intval($_GET['zid']);
	}
	if(!$gZid){
		header("location:http://realblog.zkiz.com");
		exit;
	}
	//get Settings
	$blogInfo = dbRow("SELECT * FROM zb_user WHERE id = {$gZid}");
	if(!$blogInfo){
		screenMessage("錯誤","查無此Blog");
	}
	$background = $blogInfo['background'];
	$blogfooter = $blogInfo['footer'];
	$sidebar = $blogInfo['sidebar'];
	
	$curPageNo = ($gPageNo == "") ? 0 : $gPageNo;
	$startRow_viewconlist = $curPageNo * $blogInfo['displaynum'];

    $gNavTitle = $blogInfo['blogname'];
	$htmltitle = "Real Blog - {$blogInfo['blogname']}";
	$description = "{$blogInfo['blogname']}, {$gUser}, ".htmlspecialchars($blogInfo['slogan']);
	
	
	if($gType!="" && $gType!="all"){
		$extCons = " AND type = {$gType} ";
		$temptype= $gType;
	}else{
		$extCons =  "";
		$temptype="all";
	}
	if(isset($_GET['tag']) && properTag($_GET['tag']) != ""){
		$conlistIds= getEntryByTag($_GET['tag'], 2);
		if($conlistIds){
		    $conlistIds=implode(",",$conlistIds);
		    $extCons .= " AND id IN ({$conlistIds}) ";
		}else{
			screenMessage("沒有文章","所查的Tag 沒有文章");
		}
	}
	
	
	$where_clause = "WHERE ownerid = {$gZid} AND isshow=1 {$extCons}";
	$contentList = dbAr("SELECT * FROM zb_contentpages $where_clause ORDER BY id DESC LIMIT {$startRow_viewconlist}, {$blogInfo['displaynum']}");
	$contentListCount = ceil(dbRs("SELECT count(1) FROM zb_contentpages $where_clause") / $blogInfo['displaynum'] );
	// echo "SELECT count(1) FROM zb_contentpages $where_clause";
	//$totalRows_viewconlist = dbRs("SELECT count(*) as ce FROM zb_contentpages $where_clause", $extCons==""?0:300);
	//$totalPages_viewconlist = ceil($totalRows_viewconlist/$blogInfo['displaynum'])-1; //and totalpage too ^^
	$getPages = dbAr("SELECT * FROM zb_contentpages a WHERE ownerid = {$blogInfo['id']} AND is_page=1");
	//print_r($getPages);
	//getType - Get the top list of types with numbers
	$getTypes = dbAr("SELECT count(*) as ce, b.id, b.ownerid, b.name FROM zb_contentpages a, zb_contenttype b WHERE b.ownerid = $gZid AND a.type = b.id group by b.id",[],60*15);
	
	
	//get comments
	$recentComments = dbAr("SELECT b.id, SUBSTR(a.content,1,30) as conbar FROM zb_comment a, zb_contentpages b where b.isshow = 1 and a.pageid = b.id and b.ownerid = {$gZid} ORDER BY a.time DESC LIMIT 15",[],60*15);
	
	//for sidebar.php
	foreach($recentComments as $v){
	$blogNewReply[]="<a href='http://realblog.zkiz.com/{$blogInfo['username']}/{$v['id']}'>".htmlspecialchars($v['conbar'])."</a>";
	}
	
	$rsslink = "http://realblog.zkiz.com/rssdata/{$gZid}.xml";
	
	$pageinfos = dbAr("SELECT * FROM zb_contentpages WHERE ownerid = ? AND isshow = 1 AND is_page = 0 ORDER BY id DESC LIMIT 10",$blogInfo['id'], 7200);

	foreach ($pageinfos as $item) {
		//for sidebar.php
		$blogNewArticle[] = "<a href='/{$blogInfo['username']}/{$item['id']}'>{$item['title']}</a>";
	}


	include_once('include/parsehtml.php'); 
	$mainTemplate = 'conlist';
	include(template("blogframe_{$blogInfo['blogframe']}"));
