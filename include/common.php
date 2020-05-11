<?php

include(dirname(__FILE__) . "/../../lib/common_init.php");
include(dirname(__FILE__) . "/../vendor/autoload.php");
if (!function_exists('dbRs')) {
    die("Please Check Real Lib inclusion.");
    function dbRs($a="",$b="",$c="") {}
    function dbRow($a="",$b="",$c="") {}
    function dbAr($a="",$b="",$c="") {}
    function dbQuery($a="",$b="",$c="") {}
    function insertTag($a="",$b="",$c="") {}
    function insertTagAndNotify($a="",$b="",$c="") {}
    function screenMessage($a="",$b="",$c="") {}
    function safe($a="",$b="",$c="") {}
    function GetSQLValueString($a="",$b="",$c="") {}
    function generatePagin($pageNo, $base, $queryVariableName, $totalPages){}
    function cacheVoid($key){}
    function cacheGet($key){}
	function timeago($referencedate=0, $timepointer='', $measureby=''){}
	function prevURL(){}
	function getIP(){}
}
$fbme = null;
if ($isLog && $gUsername) {
	$gId = dbRs("SELECT `id` FROM zb_user WHERE `username` = '$gUsername'");
}
$rsskey = "RB_RSS_CACHE_";

function template($str)
{
	global $isMobile;
	$templatecode_path =  dirname(__FILE__) . "/../templatecode";
	if ($isMobile) {
		//return "templatecode/mobile_$str.php";
	}
	return file_exists("$templatecode_path/normal_$str.php") ? "$templatecode_path/normal_$str.php" : "$templatecode_path/$str.php";
}

$description = "zkiz.com提供, RealBlog, 博客, 部落格, 可以自行創建, 可以自行放置Adsense或其他廣告";



function t_show($str)
{
	global $isMobile;

	$templatecode_path =  dirname(__FILE__) . "/../templatecode";
	if ($isMobile) {
		//return "$templatecode_path/mobile_$str.php";
	}
	if ($_COOKIE['rf_template'] == "mobile") {
		return "$templatecode_path/mobile_$str.php";
	}
	include(file_exists("$templatecode_path/normal_$str.php") ? "$templatecode_path/normal_$str.php" : "$templatecode_path/$str.php");
}
