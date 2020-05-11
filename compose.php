<?php 
	require_once('include/common.php');
	require_once("include/rbPosts.class.php");
	
	//settings
	$maxRows_viewconlist = 15;
	$noSidebar = true;
	$appearcus = false;
	
	//Defences
    if( str_ireplace($blackwords, '', $_POST['content']) != $_POST['content'] ){
        screenMessage("Error",'Bad Words Detected1');
	}
    if( isRussian($_POST['content']) ){
        screenMessage("Error",'Bad Words Detected2');
	}
	
    if( str_ireplace($blackwords, '', $_POST['tags']) != $_POST['tags'] ){
        screenMessage("Error",'Bad Words Detected3');
	}
	if(!$isLog){
		header("location: index.php");
		exit;
	}
	if(!$gId){
		header("location: activate.php");
		exit;
	}
	
	$right = dbRs("SELECT `right` FROM zm_members WHERE `username` = '$gUsername'");
	if($right == 0){
		screenMessage("Error", "Please validate your email.","http://members.zkiz.com/");
		exit;
	}
	
	
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if(isset($_POST['form_action'])){
		
		//modify or compose
		//checking
		//$fb = getFacebook();
		
		$right = dbRs("SELECT `right` FROM zm_members WHERE username = '$gUsername'");
		if($right == 0){
			screenMessage("Error", "Please validate your email.");
			exit;
		}
		//End of common check
		
		
		
		if ($_POST["form_action"] == "modify") {
			
			$pTid = rbPosts::modifyBlog(
				$_POST['title'],$_POST['content'],
				$_POST['content_markup']=="HTML"?"HTML":"MARKDOWN",
				$_POST['password'],$_POST['displaymode'],
				isset($_POST['isshow']),$_POST['type'],isset($_POST['renewtime']),intval($_POST['tid']),explode(",",$_POST['tags']),isset($_POST['renotify']),isset($_POST['is_page']));
			
			if($pTid == -1){
				screenMessage("Error", "Wrong Owner");
			}
			
			$modified = true;
		}
		
		if ($_POST["form_action"] == "compose") {
			$code=$_POST['g-recaptcha-response'];
			
			$arrQuery = ["secret"=>RECAPTCHA_V2_KEY,"response"=>$_POST['g-recaptcha-response'],"remoteip"=>getIP()];
			$google_check_json = file_get_contents(
				"https://www.google.com/recaptcha/api/siteverify?".http_build_query($arrQuery)
			);
			$result = json_decode($google_check_json,true);
			
			if($result["success"] != true){screenMessage("Captcha 驗證失敗","請重試","");}
			
			if(trim($_POST['title'])==""){
				$_POST['title'] = mb_substr(strip_tags($_POST['content']),0,20,"utf8");
			}
			if(trim($_POST["tags"])==""){
				$arrTags = ["Miniblog"];
				}else{
				$arrTags = explode(",",$_POST['tags']);
			}
			$pTid = rbPosts::newBlog($gId,$_POST['title'],$_POST['content'],
			'MARKDOWN',
			$_POST['password'],
			isset($_POST['isshow']),$_POST['displaymode'],$_POST['type'],$arrTags,isset($_POST['is_page']));
			
			if(isset($_POST['isshow'])){
				addMoney(1,$my['id']);
			}
			// addNews($gUsername,$_POST['title'],2,"http://realblog.zkiz.com/$gUsername/$pTid");
			cacheVoid($rsskey.$gId);
		}
		$postedURL="http://realblog.zkiz.com/$gUsername/$pTid";
	}
	$form_action = isset($_GET['tid'])?'modify':'compose';
	$gTid = intval($_GET['tid']);
	if($form_action=='modify'){
		
		$ownerid = dbRs("select ownerid from zb_contentpages a, zb_user b where a.ownerid = b.id AND a.id=$gTid");
		if ($gId!=$ownerid){
			die("Access Denied!");
		}
		$row_getcontent = dbRow("SELECT * FROM zb_contentpages WHERE id = $gTid");
		$title= $row_getcontent['title'];
		$content= htmlentities($row_getcontent['content'],ENT_COMPAT, "UTF-8");
		$thistags = getTags($gTid,2);
		}else{
		
		if(is_numeric($_GET['rftid']) && isset($_GET['rftid'])){
			$tid = intval($_GET['rftid']);
			$title = dbRs("SELECT title FROM zf_contentpages WHERE id = $tid");
			$content = dbRs("SELECT content FROM zf_reply WHERE isfirstpost = 1 AND fellowid = $tid");
			$content = nl2br($content)."<br />原帖URL: http://realforum.zkiz.com/thread.php?tid=$tid";
		}
	}
	$gettype = dbAr("SELECT * FROM zb_contenttype WHERE ownerid = $gId");
	
	
	$gDid = intval($_GET['did']);
	if($gDid){
		if(dbRs("SELECT ownerid FROM zb_contentpages WHERE id = {$gDid}") != $gId){screenMessage("Error","Access Denied!");}
		rbPosts::deleteBlog($gDid,$gId);
		header("location: /compose.php");
		exit;
	}
	



	if(!isset($_GET['box'])){
		if(isset($_GET['modisearch'])){
			$searchtxt = trim($_GET['modisearch']);
			$extcon = " AND title LIKE :title ";
			$searchArr=['title'=>"%$searchtxt%"];
		}
		if($_GET['search'] == "hidden"){
			$extcon .= " AND isshow = 0 ";
			$searchArr="";
		}	
		$page = isset($_GET['page'])?intval($_GET['page']):0;
		
		$startRow_viewconlist = $page * $maxRows_viewconlist;
		$viewconlist = dbAr("SELECT a.title as title, a.datetime as datetime, a.id as id, b.name as type, b.id as type_id
		FROM `zb_contentpages` a, zb_contenttype b 
		WHERE a.type = b.id AND a.ownerid = $gId $extcon ORDER BY id DESC LIMIT $startRow_viewconlist, $maxRows_viewconlist"
		,$searchArr	
		);
		
		if (isset($_GET['totalRows_viewconlist'])) {
			$totalRows_viewconlist = $_GET['totalRows_viewconlist'];
			} else {
			$totalRows_viewconlist = dbRs("SELECT count(1) FROM `zb_contentpages` WHERE ownerid = $gId $extcon",$searchArr);
		}
		$totalPages_viewconlist = ceil($totalRows_viewconlist/$maxRows_viewconlist)-1;
	}
	
	$gNoMathjax = true;
    $gOldBS = true;
	include(template("header"));
	if(isset($_GET['box'])){
		include(template("compose_box"));
	}else{
		include(template("compose"));
	}
	include(template("footer"));