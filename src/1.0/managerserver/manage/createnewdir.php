<?php 
$dirname=$_POST['dirname'];
//$file=$_GET['file'];
$user=$_COOKIE['admin']['username'];
$parent_id=$_GET["parent_id"];

require("../include/comment.php");
require("../include/user.class.php");
$userr =&username::getInstance();

//判断用户名是否为空
if($dirname=="")
{
/*	echo"<script language='javascript'>if(confirm('目录名不能为空！请返回')){location.href='updown.php';}else{location.href='updown.php';}</script>"*/;
echo("<script language='javascript'>alert('目录名不能为空，请重新填写！');history.go(-1);</script>");


}
else{

	//先使用用户名在members中查询用户user_id
	$sql="select user_id from members where username='".$user."'";
	$RES=mysql_query($sql,$userr->Con1);
	$MENU=mysql_fetch_array($RES);
	$user_id=$MENU["user_id"];	
	
	//检查同名目录
	$sql="select * from tb_file_all where name='".$dirname."' and filetype='1' and user_id='".$user_id."' and parent_id='".$parent_id."'";
	
	$rs=mysql_query($sql,$userr->Con1);
	if(mysql_num_rows($rs)>0)
	{
		echo"<script language='javascript'>if(confirm('不能定义同名的目录！请返回')){location.href='updown.php';}else{location.href='updown.php';}</script>";
	
	}
	else{
	//计算该目录的绝对路径
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
			
			//读出所有的文件服务器ip------------------------------------------
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
				
				
			//查找数据库中是不是有用户的文件----------------------------------
			
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
			   
			  //获取备份文件服务器的ip和存储路径
			   $replicaip=$menuip["replicaip"];
			   $sql="select * from dataserverid where serverip='".$replicaip."'";
			   $serres=mysql_query($sql,$userr->Con1);
			   $serme=mysql_fetch_array($serres);
			   $replicapath=$serme["userfilepath"];
			   
			header("location:http://".$menuip['serverip']."/www/index.php?dir=".$dirname."&username=".$user."&flag=newdir"."&parent_id=".$_GET["parent_id"]."&dirpath=".$dirpath."&replicaip=".$replicaip."&replicapath=".$replicapath."&userfilepath=".$userfilepath."&fileserverid=".$fileserverid."&manageserverip=".$_SERVER['SERVER_ADDR']); 
			
			}
				else//说明用户是第一次上传文件则按照负载均衡的策略选一台文件服务器给它
			{
			
				require_once("select_fileserver.php");
				selectserver($iparray,$filepath,$servernum,$user,$dirpath,$dirname,$parent_id);
			 
			}
	}
}
?>
