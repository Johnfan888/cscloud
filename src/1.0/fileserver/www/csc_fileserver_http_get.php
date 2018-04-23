<?php 
/*
* FS RESTFUL API - GET
* author: zfan
*/

require("../includes/fs.helper.inc.php");

/*
$userid = $_GET['userid'];
$fileserverpath = $_GET['fileserverpath'];
$filename = $_GET['filename'];
$file_id = $_GET['file_id'];
$key = $_GET['key'];
$ms_ip = $_GET['managerserverip'];
$dirpath=$fileserverpath.$userid."/";
*/

$method = $_POST['method'];
$status = $_POST['status'];
$userid = $_POST['userid'];
$fileserverpath = $_POST['fileserverpath'];
$filename = $_POST['filename'];
$file_id = $_POST['file_id'];
$key = $_POST['key'];
$ms_ip = $_POST['managerserverip'];
//TODO muti-version info
$version=$_POST['version'];
$dirpath=$fileserverpath.$userid."/";


/*	// S3 write file 
$fullPath = $dirpath.$file_id;
*/
/*	// SWIFT
$arr=substr($file_id,-3);
$dirpath=$dirpath.$arr."/";
$fullPath=$dirpath.$file_id;
*/

// UDPM
//$dirpath=$dirpath.udpm_dir2($file_id);	//2 layers
$dirpath=$dirpath.udpm_dir4($file_id);	//4 layers
if(!is_dir($dirpath))
{
	WriteLog('cscHttpGetError', "File {$filename} with ID {$file_id} not exist!");
	$result = array('result' => false,  'filename' => $filename, 'file_id' => $file_id, 'msg' => "File {$filename} with ID {$file_id} not exist");
	echo json_encode($result);
	exit;
}
$fullPath=$dirpath.$file_id;

//Check whether legal
$url = "http://{$ms_ip}/key.php" ;
$data = array('key' => $key);
$json = curlPost($url,$data,true);
if(!$json['result'])
{
	WriteLog('cscHttpGetError', "File {$filename} with ID {$file_id} no permission!");
	$result = array('result' => false,  'filename' => $filename, 'file_id' => $file_id, 'msg' => "File {$filename} with ID {$file_id} no permission!");
	echo json_encode($result);
	exit;
}
set_time_limit(0);
ini_set('memory_limit', '512M');
ob_end_clean(); // clean buffering data quietly
$readLen=readfile($fullPath);
WriteLog('cscHttpGet', " Read length $readLen for file {$filename} with ID {$file_id} and fullPath: {$fullPath}");
//@readfile($fullPath);
//@read_File($fullPath);
?>

