<?php
//!!!!!!!!!!
//DISABLED
//!!!

    require_once('include/common.php');
	$currentPage = $_SERVER["PHP_SELF"];
	
	$maxRows_getResult = 15;
	$pageNum_getResult = 0;
	if (isset($_GET['pageNum_getResult'])) {
		$pageNum_getResult = $_GET['pageNum_getResult'];
	}
	$startRow_getResult = $pageNum_getResult * $maxRows_getResult;
	
	if (isset($_GET['searchtext'])) {
		$txtSearchtext = $_GET['searchtext'];
	}
	if (isset($_GET['user_id'])) {
		$colname_user_id = intval($_GET['user_id']);
		$quser_id = " AND user_id = ".$colname_user_id;
	}else{$quser_id = " ";}
	if ($txtSearchtext != ""){
		screenMessage("Feature Disabled","Feature Disabled due to high traffic.");
		exit;
		$query_getResult = "SELECT a.id as id, title, username FROM zb_contentpages a, zb_user b WHERE a.user_id=b.id AND content LIKE :searchTxt ".$quser_id." ORDER BY create_time DESC";
		$query_limit_getResult = sprintf("%s LIMIT %d, %d", $query_getResult, $startRow_getResult, $maxRows_getResult);

        $getResult = dbAr($query_limit_getResult,['searchTxt'=>"%$txtSearchtext%"]);
		
		$queryString_getResult = "";
		if (!empty($_SERVER['QUERY_STRING'])) {
			$params = explode("&", $_SERVER['QUERY_STRING']);
			$newParams = array();
			foreach ($params as $param) {
				if (stristr($param, "pageNum_getResult") == false && 
				stristr($param, "totalRows_getResult") == false) {
					array_push($newParams, $param);
				}
			}
			if (count($newParams) != 0) {
				$queryString_getResult = "&" . htmlentities(implode("&", $newParams));
			}
		}
		$queryString_getResult = sprintf("&totalRows_getResult=%d%s", $totalRows_getResult, $queryString_getResult);
	}
include_once('templatecode/header.php'); ?>
<form method="get" action="searchresults.php" onSubmit="if(this.searchtext.value.length<2){alert('搜索內容過短');return false;}">
	<span style="font-size:14px; font-weight:bold;"><a onclick="history.go(-1)" style="cursor:pointer">回上一頁</a></span>
	<input name="searchtext" type="text" size="20" maxlength="100" />
	<input type="submit" name="Submit" value="搜尋" />
	<?php if (isset($_GET['user_id'])) { ?>
		<input name="user_id" type="hidden" value="<?php echo $_GET['user_id']; ?>" />
	<?php } ?>
</form>
<h3>搜尋結果</h3><?php if ($txtSearchtext != "") {echo "(搜索:$txtSearchtext)";}?>
<br /><br />
<?php if($totalRows_getResult==0){?>
	沒有搜尋到合適的文章<br />
<?php } ?>

<?php if ($txtSearchtext != ""){

    foreach ($getResult as $row_getResult) { ?>
<a href="/<?php echo $row_getResult['username']; ?>/<?php echo $row_getResult['id']; ?>"><?php echo $row_getResult['title']; ?></a>
<hr />
<?php }  }?>
<?php
include_once('templatecode/footer.php');

?>
