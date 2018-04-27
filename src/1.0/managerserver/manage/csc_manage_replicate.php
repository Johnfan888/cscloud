<?php
/*
* MS File Replication
* author: zfan
*/

require("../includes/init.inc.php");
require("../includes/ms.helper.inc.php");


$ms_ip = $_SERVER['SERVER_ADDR'];

// Get files to be replicated
$sql = "select * from T_FileLocation where flag = 0 order by server_ip";
$files = $db->FetchAssoc($sql);

WriteLog('cscReplicate', "Begin replication");
echo "Begin replication\n";
if($files)
{
	//Get key
	require("../admin/cls_config.php");
	$c = new cls_config();
	$c->_construct();
	$key = $c->_get("Password");

	foreach($files as $row)
	{
		WriteLog('cscReplicate', "Replicating file {$row['file_id']} MSIP $ms_ip ...");
		echo "Replicating file {$row['file_id']} ...\n";
		$data = array(
			'uid' => $row['user_id'],
			'path' => $row['file_path'],
			'id' => $row['file_id'],
			'ha_ip' => $row['ha_server_ip'],
			'ha_path' => $row['ha_file_path'],
			'ip' => $ms_ip,
			'key' => $key);
		$url = "http://{$row['server_ip']}/www/csc_fileserver_replicate_primary.php";
		$json = curlPost($url,$data,true);
		if($json['result'])
		{
			$sql = "update T_FileLocation set flag = 1 where file_id = '{$row['file_id']}'";
			$db->Query($sql);
			WriteLog('cscReplicate', "Replicating file {$row['file_id']} succeed");
			echo "Replicating file {$row['file_id']} succeed\n";
		}
		else
		{
			WriteLog('cscReplicateError', "Replicating file {$row['file_id']} failed");
			echo "Replicating file {$row['file_id']} failed\n";		}
	}
}
else
{
	WriteLog('cscReplicate', "No files to be replicated");
	echo 'No files to be replicated\n';
}
WriteLog('cscReplicate', "End replication");
echo "End replication\n";
?>
