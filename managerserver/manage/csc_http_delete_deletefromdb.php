<?php
	require("../includes/init.inc.php");
	require("../includes/file.helper.inc.php");
	require("../includes/user.helper.inc.php");
	require("./configure_class.php");
	$c = new Configuration();
	$c->_construct();
	$filename=$_POST['filename'];
	$userid=$_POST['userid'];
	$sql="select used_size from T_UserZone where user_id='{$userid}'";
	$usedsize=$db->FetchAssocOne($sql);
	$used=$usedsize['used_size']; //usedsize
	$sql1="select * from T_FileInfo where file_id='{$filename}' and user_id='{$userid}'";
	$fileinfo=$db->FetchAssocOne($sql1);
	$filesize=$fileinfo['size']; //filesize
    $used=$used-$filesize;
    
    $sql = "delete from T_FileInfo where file_id='{$filename}' and user_id='{$userid}'";
	$db->Query($sql);
	
	$sql = "delete from T_FileLocation where file_id='{$filename}' and user_id='{$userid}'";
	$db->Query($sql);
			
	$sql = "update T_UserZone set used_size='{$used}' where user_id='{$userid}'";
	$db->Query($sql);
	//echo "success";
?>