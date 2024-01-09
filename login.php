<?php
require('include/common.php'); 
//ini_set('display_errors', 1);
$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_POST['username']) && isset($_POST['password'])){
    $res = loginRealBlog($_POST['username'], $_POST['password']);
    
    if($res === true){
        die("登入成功! 請點<a href='http://realblog.zkiz.com'>這裡</a>跳回首頁!");
    }else{
        die("登入失敗! 請點<a href='http://realblog.zkiz.com'>這裡</a>跳回首頁!");
    }

}