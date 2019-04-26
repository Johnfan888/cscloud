<?php
/*
* MS RESTFUL API - PUT
* author: zfan
*/

require("../includes/init.inc.php");
require("../includes/file.helper.inc.php");
require("../includes/user.helper.inc.php");
require("../includes/log.helper.inc.php");

//Get key
require(ROOT_PATH . '/admin/cls_config.php');//ROOT_PATH服务器根目录
$c = new cls_config();
$c->_construct();
$key = $c->_get("Password");

//GET
$method = $_GET['method'];
$user = $_GET['user'];	// may use for lookup per user in future 
$filename = $_GET['filename'];
$file_id="none";

$password="do not auth"; //FIXME may need to authenticate user
$managerserverip = $_SERVER['SERVER_ADDR'];
$totalsize = "unlimited";	//FIXME may need to limit for adm user?
$usedsize = "do not care";	//FIXME may need to return to adm user client?
//xjl_2018.12.4---get user
// $sql="select user_id from T_FileInfo where file_name='$filename'";
// $userid=$db->FetchAssocOne($sql);
// $useridnum=$db->NumRows($sql);//返回数据库中的行数
// if ($useridnum > 0)
// {	$userid=$userid['user_id'];
// 	$sql="select email from T_User where user_id='$userid'";
// 	$email=$db->FetchAssocOne($sql);
// 	$user=$email['email'];
// }
//Get user_id from table members if exists
$sql = "select user_id from T_User where email ='$user'";
$filenum=$db->NumRows($sql);//返回数据库中的行数
if($filenum > 0)	//user found
{
	$userid=$db->FetchAssocOne($sql);
	$userid=$userid['user_id'];
	// found user server info
	$sql1="select * from T_UserZone where user_id='".$userid."'";
	$userserver=$db->FetchAssocOne($sql1);
	if($userserver['server_ip'] != "")
	{ 
		$fileserverip=$userserver['server_ip'];
		$replicaip=$userserver['ha_server_ip'];
		//found path
		$sql2="select file_path from T_Server where server_ip='".$fileserverip."'";
		$fileserverpath=$db->FetchAssocOne($sql2);
		$fileserverpath=$fileserverpath['file_path'];
		$sql3="select file_path from T_Server where server_ip='".$replicaip."'";
		$replicapath=$db->FetchAssocOne($sql3);
		$replicapath=$replicapath['file_path'];
		$sql4="select file_id from T_FileInfo where file_name='$filename'";
		$num=$db->NumRows($sql4);
		if($num>0)
		{
			$status="found";
			$file_id=$db->FetchAssocOne($sql4);
			$file_id=$file_id['file_id'];

			/* 
			//Multi-version support may not need for API call from file system
			$sql5="select max(version) as version from T_FileInfo where file_name='$filename'";
			$version=$db->FetchAssocOne($sql5);
			//add muti-version info
			$version=$version['version']+1;
			*/
			$sql5="select version from T_FileInfo where file_name='$filename'";
			$version=$db->FetchAssocOne($sql5);
			$version=$version['version'];
		}
		else
		{
			$version=1;
			$status="new";
		}
		//WriteLog('cscHttpPut', " num:$num filename:$filename fileID:$file_id version:$version");
	}
	else
	{
		require('../includes/loadbalance.inc.php');
		$servers = SelectFileServer();
		$fileserverip = $servers['ip'];
		$replicaip=$servers['ha_ip'];
		$fileserverpath=$servers['path'];
		$replicapath=$servers['ha_path'];
		//update table
		UpdateFileIP($userid, $fileserverip); 
		UpdateHAFileIP($userid, $replicaip);
		$status="new"; 
	}
	
	//  TODO may return version information for multi-version support  
	//add:return version,userid for mkdir
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
		"totalsize" => $totalsize,
		"usedsize" => $usedsize);
	echo json_encode($result);
}
// TODO develop login api
else //user not found
{
	$status = "unfound";
	$result = array("status" => $status);
	echo json_encode($result);
}
?>

