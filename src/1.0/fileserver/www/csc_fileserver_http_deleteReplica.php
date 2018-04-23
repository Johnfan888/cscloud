<?php
/*
* FS RESTFUL API - DELETE REPLICA
* author: zfan
*/

require("../includes/fs.helper.inc.php");

$userid = $_POST['userid'];
$filename = $_POST['filename'];
$file_id = $_POST['file_id'];
$key = $_POST['key'];
$ms_ip = $_POST['managerserverip'];
$replicapath=$_POST['replicapath'];
$dirpath=$replicapath.$userid."/";

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
$fullPath=$dirpath.$file_id;
WriteLog('cscHttpDelete', " Replica fileID: {$file_id}  fullPath: {$fullPath}");

//Check whether legal
$url = "http://{$ms_ip}/key.php" ;
$data = array('key' => $key);
$json = curlPost($url,$data,true);
if(!$json['result'])
{
	WriteLog('cscHttpDeleteError', "Replica File {$filename} with ID {$file_id} is deleting illegally!");
	exit;
}

@$flag = unlink($fullPath);
if($flag)
{
	//TODO delete empty directories
	$result = array('result' => true, 'filename' => $filename, 'file_id' => $file_id, 'msg' => "Replica File {$filename} with ID {$file_id} DELETE Succeed!");
	WriteLog('cscHttpDelete', "Replica File {$filename} with ID {$file_id} DELETE Succeed!");
	echo json_encode($result);
}
else
{
	WriteLog('cscHttpDeleteError', "Replica File {$filename} with ID {$file_id} unlink failedly! not exist?");
	//return true here
	$result = array('result' => true,  'filename' => $filename, 'file_id' => $file_id, 'msg' => "Replica File {$filename} with ID {$file_id} unlink failedly! not exist?");
	echo json_encode($result);
}
?>

