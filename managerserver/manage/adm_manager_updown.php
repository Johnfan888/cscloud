<?php 
require("../include/comment.php");
require("../include/user.class.php");

$userr = &username::getInstance();
$method = $_GET['method'];
$user = $_GET['user'];	// may use for lookup per usee in future
$filename = $_GET['filename'];
$managerserverip = $_SERVER['SERVER_ADDR'];
$totalsize = "unlimited";	//FIXME may need to limit for adm user?
$usedsize = "do not care";	//FIXME may need to return to adm user client?

//Get user_id from table members
$sql = "select * from adm_file_location where filename ='".$filename."'";
$result = mysql_query($sql, $userr->Con1);
$filenum = mysql_num_rows($result);
if($filenum > 0)	//file found, use previous information
{
	$item = mysql_fetch_array($result);
	$fileserverip = $item["serverip"];
	$fileserverpath = dirname($item["location"])."/";;
	$replicaip = $item["replicaip"];
	$replicapath = dirname($item["replicalocation"])."/";
	$status = "found";
	//  TODO may return version information for multi-version support
	echo $status."&".$fileserverip."&".$fileserverpath."&".$replicaip."&".$replicapath."&".$managerserverip."&".$totalsize."&".$usedsize;
}
else
{
	if($method == 'upload')	//Upload file first time. Choose a data server for this user
	{
		// Get data server information
		$sql = "select * from dataserverid order by serverid";
		$result = mysql_query($sql, $userr->Con1);
		$servernum = mysql_num_rows($result);
		$iparray = array();
		$filepath = array();
		for($rows = 0; $rows < $servernum; $rows++)             
		{
			$item = mysql_fetch_array($result);
			$iparray[$rows] = $item["serverip"];
			$filepath[$rows] = $item["userfilepath"];
		}
	
		// Select file server and replication server
		$rid = rand(0, $servernum - 1);
		$fileserverip = $iparray[$rid];
		$fileserverpath = $filepath[$rid];
		if($rid == $servernum - 1)
		{
			$replicaip = $iparray[0];
			$replicapath = $filepath[0];
		}
		else
		{
			$replicaip = $iparray[$rid + 1];
			$replicapath = $filepath[$rid + 1];
		}

		$status = "new";
		//  TODO may return version information for multi-version support
		echo $status."&".$fileserverip."&".$fileserverpath."&".$replicaip."&".$replicapath."&".$managerserverip."&".$totalsize."&".$usedsize;
	}
	else	// for method 'download' and 'delete'
	{
		$status = "unfound";
		//  TODO may return version information for multi-version support
		echo $status."&";
	}
}
?>

