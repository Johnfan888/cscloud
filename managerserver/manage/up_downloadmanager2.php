<?php 
require("../include/comment.php");
require("../include/user.class.php");

$userr =&username::getInstance();
$user=$_GET['user'];


//存储文件服务器的ip数组
$sql="select * from dataserverid order by serverid";
$res=mysql_query($sql,$userr->Con1);
$nums=mysql_num_rows($res);
$iparray=array();//和$iparray对应的存储文件的目录
$filepath=array();//存储用户文件的路径
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
//查找数据库中是不是有用户的文件
$sql="select * from tb_file_all where user_id='".$user_id."'";
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
		$replicaip=$menuip["replicaip"];
		$replicapath=dirname($menuip["replicalocation"])."/";
		$manageserverip=$_SERVER['SERVER_ADDR'];
		echo $menuip['serverip']."&".$replicaip."&".$replicapath."&totalsize=".$totalsize."&usedsize=".$usedsize."&".$userfilepath."&".$manageserverip;
}
else//说明用户是第一次上传文件则按照负载均衡的策略选一台文件服务器给它
{
		require_once("select_fileserver.php");
		selectserver2($iparray,$filepath,$servernum,$user,$totalsize,$usedsize);
}



?>
