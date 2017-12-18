<?php
require("../includes/init.inc.php");
require("../includes/file.helper.inc.php");
require("../includes/user.helper.inc.php");
$method=$_GET['method'];
$user=$_GET['user'];
$filename=$_GET['filename'];//UUID
$managerserverip = $_SERVER['SERVER_ADDR'];
$password="do not auth"; //FIXME may need to Authenticate user
$sql = "select user_id from T_User where email ='$user'";
$filenum=$db->NumRows($sql);
$userid=$db->FetchAssocOne($sql);
$userid=$userid['user_id'];
if($filenum > 0){
	//user found
	$status="found";
	$sql2="select * from T_FileLocation where file_id='{$filename}' and user_id='{$userid}'";
	$fileinfo=$db->FetchAssocOne($sql2);
		$fileserverip=$fileinfo['server_ip'];
		$fileserverpath = $fileinfo['file_path'];
		$replicaip=$fileinfo['ha_server_ip'];
		$replicapath = $fileinfo['ha_file_path'];
	
	// TODO mutil-version
	$sql4="select version from T_FileInfo where file_id='{$filename}'";
	$version=$db->FetchAssocOne($sql4);
	$version=$version['version'];
	echo $status."&".$fileserverip."&".$fileserverpath."&".$replicaip."&".$replicapath."&".$managerserverip."&".$userid."&".$version;
	//to fileserver
	}

else{
	//user not found
	$status = "unfound user";
	echo $status;
}
?>