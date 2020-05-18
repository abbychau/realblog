<?php
include("./include/common.php");
include("./include/credentials.php");
include("./vendor/autoload.php");
use obregonco\B2\Client;
use obregonco\B2\Bucket;


$B2CLIENT = new Client(BACKBLAZE_B2_ACCOUNT_ID, [
	'keyId' => BACKBLAZE_B2_ID,
	'applicationKey' => BACKBLAZE_B2_KEY,
	'version' => 2,
    'largeFileLimit' => 3000 * 000 * 000 * 10, // 3MB
]);

$response = [];

if (isset($_FILES['file']) && $gId) {
    // die("X");
    // $file = $_FILES['file'];
    // $filename = uniqid() . '.' . (pathinfo($file['name'], PATHINFO_EXTENSION) ? : 'png');

    // move_uploaded_file($file['tmp_name'], $uploadFolder . $filename);

    $response['filename'] = formUploadToB2("user_files/$gId");
} else {
    $response['error'] = 'Error while uploading file';
}

function formUploadToB2($folder){
    global $B2CLIENT;
    
    $path = str_replace('//','/',$folder.'/'.uniqid().rawurlencode($_FILES["file"]["name"]));
    $fhdl = fopen($_FILES["file"]["tmp_name"],'r');

    $file = $B2CLIENT->upload([
        'BucketName' => 'realblog',
        'FileName' => $path,
        'Body' => $fhdl,//fopen(dirname(__FILE__).'/404.html', 'r')
        'BucketId' => BACKBLAZE_B2_REALBLOG_BUCKET_ID
        // The file content can also be provided via a resource.
        // 'Body' => fopen('/path/to/input', 'r')
    ]);

    return "https://cdn0.zkiz.com/file/realblog/$path";
}

echo json_encode($response);