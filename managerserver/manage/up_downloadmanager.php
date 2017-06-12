 <?php
/*if(!$_COOKIE['admin']['user_id']){
	echo "未登陆";
	exit();
	
}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>无标题文档</title>
<link rel='stylesheet' type='text/css' href='css/private.css'>
</head>

<body>
<?php 
require("../include/comment.php");
require("../include/user.class.php");
$userr =&username::getInstance();
$user=$_GET['user'];

//利用递归法计算该文件的dirpath
$dirpath="/";
$parentid=$_GET["parent_id"];
while($parentid!=0)
{
	$dirpath="/".$parentid.$dirpath;
	$SQL="select parent_id from tb_file_all where id='".$parentid."'";
	$RES=mysql_query($SQL,$userr->Con1);
	$MENU=mysql_fetch_array($RES);
	$parentid=$MENU["parent_id"];
}
	//echo $dirpath;


//echo "user is:".$user;
//存储文件服务器的ip数组
$sql="select * from dataserverid order by serverid";
$res=mysql_query($sql,$userr->Con1);
$nums=mysql_num_rows($res);
$iparray=array();
$filepath=array();
  for($rows=0;$rows<$nums;$rows++)             
{             
			  //将当前菜单项目的内容导入数组             
		         $me=mysql_fetch_array($res);
				 $idarray[$rows]=$me["serverid"];
				 $iparray[$rows]=$me["serverip"];
				 $filepath[$rows]=$me["userfilepath"];
}
$servernum=$nums;//存储文件服务器的个数

//使用用户名在members中查询用户user_id
$sql="select user_id from members where username='".$user."'";
$RES=mysql_query($sql,$userr->Con1);
$MENU=mysql_fetch_array($RES);
$user_id=$MENU["user_id"];	

//获取该用户可使用的磁盘空间
$sql="select * from filesize where username='".$user."'";
$result=mysql_query($sql,$userr->Con1);
$array=mysql_fetch_array($result);
$totalsize=$array["totalsize"];
$usedsize=$array["usedsize"];

//查找数据库中是不是有用户的文件
$sql="select * from tb_file_all where user_id='".$user_id."' order by id";
$result=mysql_query($sql,$userr->Con1);
$filenum=mysql_num_rows($result);
if($filenum>0)//说明用户以前上传过文件，则查找存储用户文件的fs的ip
{
		$menu=mysql_fetch_array($result);
		$sql="select * from tb_file_location where id ='".$menu["id"]."'";
		$resultt=mysql_query($sql,$userr->Con1);
		$menuip=mysql_fetch_array($resultt);
		$fileserverip=$menuip["serverip"];
		
		$sql="select * from dataserverid where serverip='". $fileserverip."'";
		$serres=mysql_query($sql,$userr->Con1);
		$serme=mysql_fetch_array($serres);
		$fileserverid=$serme["serverid"];
		$userfilepath=$serme["userfilepath"];
		
		//获取备份文件服务器的ip和存储路径
		$replicaip=$menuip["replicaip"];
		$sql="select * from dataserverid where serverip='".$replicaip."'";
		$serres=mysql_query($sql,$userr->Con1);
		$serme=mysql_fetch_array($serres);
		$replicapath=$serme["userfilepath"];
		
		header("location:http://".$menuip['serverip']."/www/index.php?username=".$user."&flag=upload"."&parent_id=".$_GET["parent_id"]."&dirpath=".$dirpath."&replicaip=".$replicaip."&replicapath=".$replicapath."&userfilepath=".$userfilepath."&fileserverid=".$fileserverid."&totalsize=".$totalsize."&usedsize=".$usedsize."&manageserverip=".$_SERVER['SERVER_ADDR']); 


}
else//说明用户是第一次上传文件则按照负载均衡的策略选一台文件服务器给它
{

		require_once("select_fileserver.php");
		selectserver1($iparray,$filepath,$servernum,$user,$dirpath,$totalsize,$usedsize);
 
}







?>








</body>
</html>
