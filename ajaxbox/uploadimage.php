<?php

	error_reporting(E_ERROR | E_PARSE);
	ini_set('display_errors',true);
	$memberPath = $_SERVER['DOCUMENT_ROOT']."/../members.zkiz.com/";
//echo $memberPath.'/include/common.php';
	require_once($memberPath.'/include/common.php');

	if(!$isLog){die("Not Log in");}

	$tempFile = $_FILES['Filedata']['tmp_name'];
	
	$targetPath = "{$memberPath}/storage/{$gId}/";
	$pathParts = pathinfo($_FILES['Filedata']['name']);
	$ext = $pathParts["extension"]?$pathParts["extension"]:"jpg";
	$name = $pathParts["filename"];
	
	/*
	if(strtolower($ext)=='php'){
		echo 'Invalid file type.';
	}
	*/
	//$tid = intval($_GET['tid']);
	/*
		if(in_array(strtolower($ext),array("bmp","jpg","jpeg","png")){
		if(300000 < $_FILES['Filedata']['size']){
		die("0");
		}
		}
	*/ 
	$fileName = date("md_His") . $name .".$ext";
	$targetFile =  str_replace('//','/',$targetPath) . $fileName;//$_FILES['Filedata']['name'];
	//die($targetFile);
	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	// $fileTypes  = str_replace(';','|',$fileTypes);
	// $typesArray = split('\|',$fileTypes);
	$fileParts  = pathinfo($_FILES['Filedata']['name']);
	
	if(6000000 < $_FILES['Filedata']['size']){
		die("Too Large");
	}
	
	if (stristr($_FILES['Filedata']['name'],".php")) {
		$targetFile .= ".txt";
	}
	$dir = str_replace('//','/',$targetPath);
	if (!file_exists($dir)) {
		mkdir($dir, 0755, true);
	}
	move_uploaded_file($tempFile,$targetFile);

	echo "http://members.zkiz.com/storage/{$gId}/{$fileName}";