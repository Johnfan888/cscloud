<?php 
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();
$id=$_POST['id'];
echo "id=".$id;
$owner=$_POST['owner'];
$userDirSize=$_POST['dirsize'];
$serverid=$_POST['serverid'];
//写日志-----------------------------------------------
	 if(!is_file("/var/log/csc/data_log.txt"))
	{
	 $fp=fopen("/var/log/csc/data_log.txt","w");
	  fclose($fp);
	}
	$fp = fopen("/var/log/csc/data_log.txt","a");
	fwrite($fp,date("Y-m-d H:i:s")."		");
	fwrite($fp,"delete		");
	fwrite($fp,$id."	");
	fwrite($fp,$owner."\n");
	fclose($fp);
//--------------------------------------------------------	
//删除tb_file_location中的信息
    $sql="delete from tb_file_location where id='".$id."'";
    mysql_query($sql,$user->Con1);
    //删除tb_file_cache中的信息
	$sql="delete from tb_file_cache where id='".$id."'";
    $res=mysql_query($sql,$user->Con1);
	//删除tb_file_all中的信息
	$sql="delete from tb_file_all where id='".$id."'";
    $res=mysql_query($sql,$user->Con1);
 
//更新用户文件的目录大小
	$sql="select * from dataserverid where serverid='".$serverid."'";
	$res=mysql_query($sql,$user->Con1);
	$MENU=mysql_fetch_array($res);

	$sql="select * from filesize where serverip='".$MENU["serverip"]."' and username='".$owner."'";
	$res=mysql_query($sql,$user->Con1);
 
	if(mysql_num_rows($res)>0) 
	{
		 $sql="update filesize set usedsize='".$userDirSize."' where username='".$owner."' and serverip='".$MENU["serverip"]."'";
 		 mysql_query($sql,$user->Con1);
	}
	else
	{
		 $sql="insert into filesize(serverip,username,usedsize) values('".$MENU["serverip"]."','".$owner."','".$userDirSize."')";
 		 mysql_query($sql,$user->Con1);
}
	

?>