<?php
function send($url,$post_data) //Curl╣Всц
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	//curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$result=curl_exec($ch);
	curl_close($ch);
	return $result;
}
require("../includes/init.inc.php");
require("../includes/file.helper.inc.php");
require("../includes/user.helper.inc.php");
$method=$_GET['method']; //Delete
$user=$_GET['user'];
$filename=$_GET['filename'];//UUID
$managerserverip = $_SERVER['SERVER_ADDR'];
$password="do not auth"; //FIXME may need to Authenticate user password
$sql="select user_id from T_User where email ='$user'";
$userinfo=$db->FetchAssocOne($sql);
$userid=$userinfo['user_id']; //uid
if($userid){
	$status="found";
	$sql1="select * from T_FileInfo where file_id='{$filename}' and user_id='{$userid}'";
	$fileinfo=$db->FetchAssocOne($sql1);
	//$filesize=$fileinfo['size']; //filesize
	$version=$fileinfo['version']; 
	
	$sql4="select * from T_FileLocation where file_id ='{$filename}' and user_id='{$userid}'";
	$filelocation=$db->FetchAssocOne($sql4);
	$fileserverip=$filelocation['server_ip'];
	$fileserverpath=$filelocation['file_path'];
	$replicaip=$filelocation['ha_server_ip'];
	$replicapath=$filelocation['ha_file_path'];
	echo $status."&".$fileserverip."&".$fileserverpath."&".$replicaip."&".$replicapath."&".$managerserverip."&".$userid."&".$version;
	}
		
else{
	$status="unfound user";
	echo $status;
}
?>