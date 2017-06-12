<?php
require("../includes/init.inc.php");
require("../includes/file.helper.inc.php");
require("../includes/user.helper.inc.php");
//GET
$method = $_GET['method'];  //upload
$user = $_GET['user'];	// may use for lookup per user in future 
$filename = $_GET['filename']; // UUID

$password="do not auth"; //FIXME may need to Authenticate user
$managerserverip = $_SERVER['SERVER_ADDR']; //managerserverip
$totalsize = "unlimited";	//FIXME may need to limit for adm user?
$usedsize = "do not care";	//FIXME may need to return to adm user client?

//Get user_id from table members if exists
$sql = "select user_id from T_User where email ='$user'";
$filenum=$db->NumRows($sql);
if($filenum > 0)	//user found
{
	$userid= $db->FetchAssocOne($sql);
	$userid=$userid['user_id'];
	// found user server info
	$sql1="select * from T_UserZone where user_id='".$userid."'";
	$userserver=$db->FetchAssocOne($sql1);
	if($userserver['server_ip'] != ""){ 
		$fileserverip=$userserver['server_ip'];
		$replicaip=$userserver['ha_server_ip'];
		//found path
		$sql2="select file_path from T_Server where server_ip='".$fileserverip."'";
		$fileserverpath=$db->FetchAssocOne($sql2);
		$fileserverpath=$fileserverpath['file_path'];
		$sql3="select file_path from T_Server where server_ip='".$replicaip."'";
		$replicapath=$db->FetchAssocOne($sql3);
		$replicapath=$replicapath['file_path'];
		 //TODO multi-version
		$sql4="select file_id from T_FileInfo limit  where file_id='$filename'";
		$num=$db->NumRows($sql4);
		if($num>0){
		   $status="found"; //multi-version
		   $sql5="select max(version) as version from T_FileInfo where file_id='$filename'";
		   $version=$db->FetchAssocOne($sql5);
		   //add muti-version info
		   $version=$version['version']+1;
		}
		else{
			$version=1;
			$status="new"; //not upload previous
		}
		
	}
	else{
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
	echo $status."&".$fileserverip."&".$fileserverpath."&".$replicaip."&".$replicapath."&".$managerserverip."&".$userid."&".$version."&".$totalsize."&".$usedsize;
}
// TODO develop login api
else //user not found
{
		$status = "unfound";
		//  TODO may return version information for multi-version support
		echo $status."&";
	}
?>


