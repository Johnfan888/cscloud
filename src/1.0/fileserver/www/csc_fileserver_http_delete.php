<?php
/*
* FS RESTFUL API - DELETE
* author: zfan
*/

require("../includes/fs.helper.inc.php");

$method = $_POST['method'];
$userid = $_POST['userid'];
$fileserverpath = $_POST['fileserverpath'];
$filename = $_POST['filename'];
$file_id = $_POST['file_id'];
$key = $_POST['key'];
$ms_ip = $_POST['managerserverip'];
// HA info
$replicaip=$_POST['replicaip'];
$replicapath=$_POST['replicapath'];
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
$fullPath=$dirpath.$file_id;
WriteLog('cscHttpDelete', " fileID: {$file_id}  fullPath: {$fullPath}");

//Check whether legal
$url = "http://{$ms_ip}/key.php" ;
$data = array('key' => $key);
$json = curlPost($url,$data,true);
if(!$json['result'])
{
	WriteLog('cscHttpDeleteError', "File {$filename} with ID {$file_id} is deleting illegally!");
	exit;
}

$size=filesize($fullPath);
@$flag = unlink($fullPath);
if($flag)
{
	//TODO delete empty directories

	$post_data = array("file_id" => $file_id, "filename" => $filename, "userid" => $userid,	"size" => $size, "version" => $version);
	$url = "http://".$ms_ip."/manage/csc_http_delete_deletefromdb.php";
	$json = curlPost($url,$post_data,true);
	if(!$json['result'])
	{
		WriteLog('cscHttpDeleteError', "File {$filename} with ID {$file_id} failed to delete DB!");
		exit;
	}
	$post_data = array("file_id" => $file_id, "filename" => $filename, "userid" => $userid, "key" => $key, "managerserverip" => $ms_ip, "replicapath" => $replicapath);
	$url = "http://".$replicaip."/www/csc_fileserver_http_deleteReplica.php";
	$json = curlPost($url,$post_data,true);
	if(!$json['result'])
	{
		WriteLog('cscHttpDeleteError', "File {$filename} with ID {$file_id} failed to delete replica!");
		exit;
	}
	$result = array('result' => true, 'filename' => $filename, 'file_id' => $file_id, 'msg' => "File {$filename} with ID {$file_id} DELETE Succeed!");
	WriteLog('cscHttpDelete', "File {$filename} with ID {$file_id} DELETE Succeed!");
	echo json_encode($result);
}
else
{
	WriteLog('cscHttpDeleteError', "File {$filename} with ID {$file_id} unlink failedly!");
	$result = array('result' => false,  'filename' => $filename, 'file_id' => $file_id, 'msg' => "File {$filename} with ID {$file_id} DELETE Failed!");
	echo json_encode($result);
}
?>

