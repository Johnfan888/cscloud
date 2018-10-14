<?php
/*
* FS RESTFUL API - PUT
* author: zfan
*/

require("../fs_includes/fs.helper.inc.php");

$method = $_POST['method'];
$status = $_POST['status'];
$userid = $_POST['userid'];
$fileserverpath = $_POST['fileserverpath'];
$filename = $_POST['filename'];
$file_id = $_POST['file_id'];
$key = $_POST['key'];
$ms_ip = $_POST['managerserverip'];
// HA info
$replicaip=$_POST['replicaip'];
$replicapath=$_POST['replicapath'];
//TODO muti-version info
$version=$_POST['version'];
$dirpath=$fileserverpath.$userid."/";

/*	// S3 write file 
$file_id = UniqueName();
$fullPath = $dirpath.$file_id;
*/
/*	// SWIFT
$file_id = UniqueName();
$arr=substr($file_id,-3);
$dirpath=$dirpath.$arr."/";
$fullPath=$dirpath.$file_id;
*/

// UDPM
if($status == "new")
{	
	$file_id = UniqueName();
	WriteLog('cscHttpPut', " new file. create ID: {$file_id}");
}
else
{
	// Multi-version support may not need for ingesting from FS API
	WriteLog('cscHttpPut', " existing file. file ID: {$file_id}");
}
$dirpath=$dirpath.udpm_dir($file_id);	//4 layers
if(!is_dir($dirpath)) {
	mkdir($dirpath, 0755, "-p");
}
$fullPath=$dirpath.$file_id;
WriteLog('cscHttpPut', " fileID: {$file_id}  fullPath: {$fullPath}");

//Check whether legal
$url = "http://{$ms_ip}/key.php" ;
$data = array('key' => $key);
$json = curlPost($url,$data,true);
if(!$json['result'])
{
	WriteLog('cscHttpPutError', "File {$filename} with ID {$file_id} is saving illegally!");
	exit;
}

if(move_uploaded_file($_FILES['file']['tmp_name'], $fullPath))
{
	WriteLog('cscHttpPut', "File {$filename} with ID {$file_id} saved successfully!");
	$size=filesize($fullPath);
	$post_data = array(
		"file_id" => $file_id,
		"filename" => $filename,
		"userid" => $userid,
		"fileserverip" => $_SERVER['SERVER_ADDR'],
		"filelocation" => $fullPath,
		"fileserverpath" => $fileserverpath,
		"replicaip" => $replicaip,
		"replicapath" => $replicapath,
		"size" => $size,
		"version" => $version);
	if($status == "new")
		$url = "http://".$_POST['managerserverip']."/manage/csc_http_put_inserttodb.php";
	else
		$url = "http://".$_POST['managerserverip']."/manage/csc_http_put_updatetodb.php";
	$json = curlPost($url,$post_data,true);
	if(!$json['result'])
	{
		if($status == "new")
			WriteLog('cscHttpPutError', "File {$filename} with ID {$file_id} failed to insert DB!");
		else
			WriteLog('cscHttpPutError', "File {$filename} with ID {$file_id} failed to update DB!");
		unlink($fullPath);
		exit;
	}
	$result = array('result' => true, 'filename' => $filename, 'file_id' => $file_id, 'msg' => "File {$filename} with ID {$file_id} PUT Succeed!");
	echo json_encode($result);
}
else
{
	WriteLog("'cscHttpPutError', File {$filename} with ID {$file_id} saved failedly!");
	$result = array('result' => false,  'filename' => $filename, 'file_id' => $file_id, 'msg' => "File {$filename} with ID {$file_id} PUT Failed!");
	echo json_encode($result);
}
?>
