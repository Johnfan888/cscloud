<?php
/*
* FS File Replication - Primary
* author: zfan
*/

require("../fs_includes/fs.helper.inc.php");

if(!empty($_POST['uid']))
{	
	$uid = $_POST['uid']; //user id
	$path = $_POST['path']; //file base path
	$id = $_POST['id']; //file id
	$ha_ip = $_POST['ha_ip']; //replica ip
	$ha_path = $_POST['ha_path']; //replica base path
	$ms_ip = $_POST['ip'];
	$key = $_POST['key'];
	
	$dirpath = $path.$uid."/";
	$dirpath = $dirpath.udpm_dir($id);	//4 layers
	$fullPath = $dirpath.$id;
	WriteLog('cscReplicate', "Replicating file with ID $id FULLPATH $fullPath MSIP $ms_ip to replica server $ha_ip");
	$data = array(
		'uid' => $uid,
		'path' => $ha_path,
		'id' => $id,
		'ip' => $ms_ip,
		'key' => $key,
		//'file' => new CURLFile($fullPath)); //for php version 5.5.16 or above
		'file' => "@{$fullPath}");	// For php version 5.2.6 (default in sles11sp1);
	$url = "http://{$ha_ip}/www/csc_fileserver_replicate_secondary.php";
	$json = curlPost($url,$data,true);
	if($json['result'])
	{
		WriteLog('cscReplicate', "File with ID $id replication succeed!");
		$result = array('result' => true, 'file_id' => $id, 'msg' => "File with ID $id replication succeed!");
		echo json_encode($result);
	}
	else
	{
		WriteLog('cscReplicateError', "File with ID $id replication failed!");
		$result = array('result' => false, 'file_id' => $id, 'msg' => "File with ID $id replication failed!");
		echo json_encode($result);
	}
}
?>
