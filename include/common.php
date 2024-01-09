<?php
// show errors
ini_set('display_errors', 1);

include(dirname(__FILE__) . "/../lib/common_init.php");
include(dirname(__FILE__) . "/../vendor/autoload.php");

class ParsedownExtensions extends \ParsedownExtra
{
   protected $newtablink = false;

   public function setAllLinksNewTab($b)
   {
      $this->newtablink = $b===true;
   }

   protected function applyOwnLinkStuff(&$link)
   {
      // **snipp**
      if($this->newtablink===true)
      {
         $link['target'] = "_blank";
      }
      // **snipp**
   }

   // overwritten methods from parsedown
   protected function inlineLink($Excerpt) {
      $temp = parent::inlineLink($Excerpt);
      if(is_array($temp))
      {
         if(isset($temp['element']['attributes']['href']))
         {
            $this->applyOwnLinkStuff($temp['element']['attributes']);
         }
         return $temp;
      }
   }

   protected function inlineUrl($Excerpt)
   {
      $temp = parent::inlineUrl($Excerpt);
      if(is_array($temp))
      {
         if(isset($temp['element']['attributes']['href']))
         {
            $this->applyOwnLinkStuff($temp['element']['attributes']);
         }
         return $temp;
      }
   }

}


$fbme = null;
if ($isLog && $gUsername) {
	$gId = dbRs("SELECT `id` FROM zb_user WHERE `username` = '$gUsername'");
}
$rsskey = "RB:RSS_CACHE";

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
