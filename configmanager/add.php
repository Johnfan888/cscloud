<?php
	session_start();
	if($_SESSION['name']=="")
	{
	echo "尚未登陆！";
	exit();
	
	}
?>
<?php 
require("conn/conn.php");
$ip=$_POST["ip"];
$status=$_POST["status"];
$cpu=$_POST["cpu"];
$memory=$_POST["memory"];
$disk=$_POST["disk"];
$userfilepath=$_POST["userfilepath"];
if($_POST["postsize"]==0||$_POST["postsize"]=="")
{
	$post_size=1000000000;
}
else
{
	if($_POST['postsize']<0)
	{
		 echo"<script language='javascript'>if(confirm('POST_SIZE填写有误！请返回！')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
	}
	else
	{
		$post_size=$_POST["postsize"]*1000000;
	}

}
$strlen=strlen($userfilepath);
$array=str_split($userfilepath);
if($array[0]!='/')//$userfilepath未以"/"开头
{
	$str="/".$str;
}
if($array[$strlen-1]!='/')//$userfilepath未以"/"结束
{
	$str=$str."/";
}


//先判断该ip是否已经配置
if($ip=="")
{
 echo"<script language='javascript'>if(confirm('ip地址不能为空！')){location.href='addnewserver.php';}else{location.href='addnewserver.php';}</script>";

}
else{
		$sq="select * from ip_table where ip_address='".$ip."'";
		$res=mysql_query($sq,$conne->getconnect());
		$nums=mysql_num_rows($res);
		if($nums>0)
		{
					 echo"<script language='javascript'>if(confirm('该ip地址已经配置，请返回查看！')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
		}
		else{
				if($status=="manager")
				{
					   $sql="select * from ip_table where status='manager'";
					   $result=mysql_query($sql,$conne->conn);
					   $num=mysql_num_rows($result);
					   $menu=mysql_fetch_array($result);
					   if($num==1)
					   {
						 echo"<script language='javascript'>if(confirm('集群系统中只能有一台manager它的ip为：".$menu["ip_address"]."，请返回！')){location.href='addnewserver.php';}else{location.href='addnewserver.php';}</script>";
						}
					   else
					   {
							$sql="insert into ip_table values('','".$ip."','manager','".$cpu."','".$memory."','".$disk."','".$userfilepath."','".$post_size."','','','0')";
						  mysql_query($sql,$conne->getconnect());
						  //echo "2";
						  echo"<script language='javascript'>if(confirm('添加成功，请返回！')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
					   } 
				}
				else
				{
					 $sql="insert into ip_table values('','".$ip."','".$status."','".$cpu."','".$memory."','".$disk."','".$userfilepath."','".$post_size."','','','0')";
					  mysql_query($sql,$conne->getconnect());
					 //echo"3";
						  echo"<script language='javascript'>if(confirm('添加成功，请返回！')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
					 
				}
		}
}
?>