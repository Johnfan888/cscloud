<?php
/*
* MS RESTFUL API - DELETE
* author: zfan
*/

require("../includes/init.inc.php");
require("../includes/log.helper.inc.php");

//Get key
require(ROOT_PATH . '/admin/cls_config.php');
$c = new cls_config();
$c->_construct();
$key = $c->_get("Password");

//GET
$method = $_GET['method'];
$user = $_GET['user'];	// may use for lookup per user in future 
$filename = $_GET['filename'];
$file_id = "none";

$password = "do not auth"; //FIXME may need to authenticate user
$version = "do not support";	//TODO may not need for ingesting via fs API
$managerserverip = $_SERVER['SERVER_ADDR'];


//xjl_2018.12.4---get user

$sql="select user_id from T_FileInfo where file_name='$filename'";
$userid=$db->FetchAssocOne($sql);
$userid=$userid['user_id'];

$sql="select email from T_User where user_id='$userid'";
$email=$db->FetchAssocOne($sql);
$user=$email['email'];


//Get user_id from table members if exists
$sql = "select user_id from T_User where email ='$user'";
$filenum = $db->NumRows($sql);
if($filenum > 0)	//user found
{
	$userid = $db->FetchAssocOne($sql);
	$userid = $userid['user_id'];
	// found user server info
	$sql = "select * from T_UserZone where user_id='".$userid."'";
	$userserver = $db->FetchAssocOne($sql);
	if($userserver['server_ip'] != "")
	{ 
		$sql = "select file_id from T_FileInfo where file_name='$filename' and user_id='{$userid}'";
		$num = $db->NumRows($sql);
		if($num > 0)
		{
			$status = "found";
			$file_id = $db->FetchAssocOne($sql);
			$file_id = $file_id['file_id'];
			$sql = "select * from T_FileLocation where file_id='{$file_id}'";
			$fileLocation = $db->FetchAssocOne($sql);
			//Found file location information
			if($fileLocation)
			{
				//Check whether primary server of this file is normal
				$sql = "select status from T_Server where server_ip='{$fileLocation['server_ip']}'";
				$sqlData = $db->FetchAssocOne($sql);
				if($sqlData['status'] == 1)
				{
					$fileserverip = $fileLocation['server_ip'];
					$fileserverpath = $fileLocation['file_path'];
					//Check whether secondary server of this file is normal
					$sql = "select status from T_Server where server_ip='{$fileLocation['ha_server_ip']}'";
					$sqlData = $db->FetchAssocOne($sql);
					if($sqlData['status'] == 1)
					{
						$replicaip = $fileLocation['ha_server_ip'];
						$replicapath = $fileLocation['ha_file_path'];
						$isReplicated = $fileLocation['flag'];
					}
					else
					{	
						$status = "secondaryserverdown";
						$result = array("status" => $status);
						WriteLog('cscHttpDeleteError', "Secondary server down.  status:{$sqlData['status']}");
						echo json_encode($result);
						exit;
					}
				}
				else
				{
					$status = "primaryserverdown";
					$result = array("status" => $status);
					WriteLog('cscHttpDeleteError', "Primary server down.  status:{$sqlData['status']}");
					echo json_encode($result);
					exit;
				}
	
				$result = array(
					"key" => $key,
					"status" => $status,
					"fileserverip" => $fileserverip,
					"fileserverpath" => $fileserverpath,
					"replicaip" => $replicaip,
					"replicapath" => $replicapath,
					"managerserverip" => $managerserverip,
					"userid" => $userid,
					"filename" => $filename,
					"file_id" => $file_id,
					"version" => $version,
					"isReplicated" => $isReplicated);
				WriteLog('cscHttpDelete', " status:$status isReplicated:$isReplicated filename:$filename fileID:$file_id user:$user ip:$fileserverip path:$fileserverpath");
				echo json_encode($result);
			}
			else
			{
				$status = "unfoundfilelocation";
				$result = array("status" => $status);
				WriteLog('cscHttpDeleteError', " status:$status filename:$filename fileID:$file_id user:$user");
				echo json_encode($result);
			}
		}
		else
		{
			$status = "unfoundfile";
			$result = array("status" => $status);
			WriteLog('cscHttpDeleteError', " status:$status filename:$filename user:$user");
			echo json_encode($result);
		}
	}
	else
	{
		$status = "unfoundserver";
		$result = array("status" => $status);
		WriteLog('cscHttpDeleteError', " status:$status filename:$filename user:$user");
		echo json_encode($result);
	}
}
else //user not found
{
	$status = "unfounduser";
	$result = array("status" => $status);
	WriteLog('cscHttpDeleteError', " status:$status filename:$filename user:$user");
	echo json_encode($result);
}
?>

