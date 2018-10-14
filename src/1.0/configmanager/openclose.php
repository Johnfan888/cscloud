<?php
	session_start();
	header('Content-Type:text/html;charset=gb2312');
	if($_SESSION['name']=="")
	{
	echo "ÉÐÎ´µÇÂ½£¡";
	exit();
	
	}
require("conn/conn.php");
$flag=$_GET["act"];
$userid=$_GET["user_id"];

if($flag=="open")
{
$sql="update tb_member set active=1 where id='".$userid."'";
mysql_query($sql,$conne->getconnect());
}
else if($flag=="close")
{
$sql="update tb_member set active=0 where id='".$userid."'";
mysql_query($sql,$conne->getconnect());
}
else if($flag=="del_user")
{
$sql="delete from  tb_member where id='".$userid."'";
mysql_query($sql,$conne->getconnect());
}
echo "<a href='showuser.php'>·µ»Ø</a>";


?>
