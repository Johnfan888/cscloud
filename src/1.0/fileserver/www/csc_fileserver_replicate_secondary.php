<?php
/*
* FS File Replication - secondary
* author: zfan
*/

require("../fs_includes/fs.helper.inc.php");

if(!empty($_FILES))
{
	$uid = $_POST['uid']; //user id
	$path = $_POST['path']; //replica file base path
	$id = $_POST['id']; //file id
	$ms_ip = $_POST['ip'];
	$key = $_POST['key'];

	//Check whether legal
	$url = "http://$ms_ip/key.php";
	$data = array('key' => $key);
	$json = curlPost($url,$data,true);
	if(!$json['result'])
	{
		WriteLog('cscReplicateError', "File with ID $id and Key $key URL $url MSIP $ms_ip is replicating illegally!");
		$result = array('result' => false, 'file_id' => $id, 'msg' => "File with ID $id and Key $key is replicating illegally!");
		echo json_encode($result);
	}
	else
	{
		$dirpath = $path.$uid."/";
		$dirpath = $dirpath.udpm_dir($id);	//4 layers
		if(!is_dir($dirpath))
		{
			mkdir($dirpath, 0755, "-p");
		}
		$fullPath = $dirpath.$id;
		WriteLog('cscReplicate', " Begin replicating file with ID $id fullpath $fullPath ...");
		if(move_uploaded_file($_FILES['file']['tmp_name'], $fullPath))
		{
			WriteLog('cscReplicate', "File with ID {$id} replicated successfully!");
			$result = array('result' => true, 'file_id' => $id, 'msg' => "File with ID $id replicated successfully!");
			echo json_encode($result);
		}
		else
		{
			WriteLog('cscReplicateError', "File with ID $id replicated failedly!");
			$result = array('result' => false, 'file_id' => $id, 'msg' => "File with ID $id replicated failedly!");
			echo json_encode($result);
		}
	}
}
