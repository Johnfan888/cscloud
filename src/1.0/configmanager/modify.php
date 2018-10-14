<?php 
	session_start();
	header('Content-Type:text/html;charset=gb2312');
	if($_SESSION['name']=="")
	{
	echo "尚未登陆！";
	exit();
	
	}

require("conn/conn.php");
$ip=$_POST["ipinfo"];
$status=$_POST["statusinfo"];
$cpu=$_POST["cpuinfo"];
$memory=$_POST["memoryinfo"];
$disk=$_POST["diskinfo"];
$userfilepath=$_POST["userfilepath"];
if($ip=="")
{
echo "<a href='configserver.php'>ip不能为空！</a>";
}
else{
	
		if($status=="manager")
		{
			   $sql="select * from ip_table where status='manager'and ip_address='".$ip."'";
			   $result=mysql_query($sql,$conne->getconnect());
			   $num=mysql_num_rows($result);
			   if($num==1)
			   {
				$sql1="update  ip_table set cpu='".$cpu."',memory='".$memory."',disk='".$disk."',userfilepath='".$userfilepath."',status='manager' where ip_address='".$ip."'";
					  mysql_query($sql1,$conne->getconnect());
			   echo"<script language='javascript'>if(confirm('修改成功，请返回！')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
			   }
			   else{
			   $sql2="select * from ip_table where status='manager'";
			   $result2=mysql_query($sql2,$conne->getconnect());
			   $num2=mysql_num_rows($result2);
				 $menu=mysql_fetch_array($result2);
					 if($num2>0)
					 {
				   echo"<script language='javascript'>if(confirm('>整个集群存储系统中已经存在一个manager，它的ip为：".$menu["ip_address"]."')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
					 
					 }
					 else{
					 
					$sql3="update  ip_table set cpu='".$cpu."',memory='".$memory."',disk='".$disk."',userfilepath='".$userfilepath."',status='manager' where ip_address='".$ip."'";
					  mysql_query($sql3,$conne->getconnect());
						 echo"<script language='javascript'>if(confirm('修改成功，请返回！')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
					 
					 }
			   } 
		}
		else{
			 
			 // $sql="update ip_table set cpu='"."', memory='',disk='', status='".$status."' where ip_address='".$ip."'";
			 $sql="update ip_table set cpu='".$cpu."', memory='".$memory."',disk='".$disk."',userfilepath='".$userfilepath."', status='".$status."' where ip_address='".$ip."'";
			  mysql_query($sql,$conne->getconnect());
				 echo"<script language='javascript'>if(confirm('修改成功，请返回！')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
		
		
		}
}
?>
