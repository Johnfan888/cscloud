<?php
require("../include/comment.php");
require("../include/user.class.php");
require("./configure_class.php");
$c = new Configuration();
$c->_construct();
$order_num=$c->_get("Cachecount");
$user =&username::getInstance();
$id=$_POST['id'];
$name=$_POST['dirname'];
$url=$_POST['url'];
$filesize=$_POST['filesize'];
$type=$_POST['type'];
$createtime=$_POST['createtime'];
$visittime=$_POST['visittime'];
$modifytime=$_POST['modifytime'];
$owner=$_POST['owner'];
$path=$url;
$userDirSize=0;
$serverid=$_POST['serverid'];
$parent_id=$_POST['parent_id'];
$replicaip=$_POST['replicaip'];
$replicapath=$_POST['replicapath'];

//把新建的目录信息插入到tb_file_all,tb_file_cache,tb_file_location中---------------------------------
//先使用用户名在members中查询用户user_id
$sql="select user_id from members where username='".$owner."'";
$RES=mysql_query($sql,$user->Con1);
$MENU=mysql_fetch_array($RES);
$user_id=$MENU["user_id"];	
//把新建的目录信息插入tb_file_all
$sql="insert into tb_file_all values('".$id."','".$parent_id."','".$name."','','".$filesize."','".$type."','".$createtime."','".$visittime."','".$modifytime."','".$user_id."')";
mysql_query($sql,$user->Con1);

//获取该文件的上级目录名
$sql="select name from tb_file_all where id='".$parent_id."'";
$res=mysql_query($sql,$user->Con1);
$menu=mysql_fetch_array($res);
$dirname=$menu["name"];

//插入cache表,首先确定tb_file_cache表的大小
$SQL="select * from tb_file_all";
$res=mysql_query($SQL,$user->Con1);
$num=mysql_num_rows($res);//查看tb_file_all中有多少条记录，cache为all的1/4
$cachenum=ceil($num/4);
if($cachenum<100)
{
	$order_num=100;//cache表至少有100条记录
}
else{
	$order_num=$cachenum;
}

//检查cache表存储容量是否达到上限
$sql="select * from tb_file_cache";
$res=mysql_query($sql,$user->Con1);
$num=mysql_num_rows($res);
if(($order_num-$num)>=1)
{
	if($parent_id==0)
	{
		$dirname='/';
	}
	$sql="insert into tb_file_cache values('".$id."','".$parent_id."','".$name."','".$dirname."','1','".$filesize."','".$type."','".$modifytime."','".$user_id."')";
	mysql_query($sql,$user->Con1);
}
else//否则先删除一条记录再插入(删除是将修改时间最早的进行删除)
{
	$sql="delete from tb_file_cache order by modifytime asc limit 1";
	mysql_query($sql,$user->Con1);
	$sql="insert into tb_file_cache values('".$id."','".$parent_id."','".$name."','".$dirname."','1','".$filesize."','".$type."','".$modifytime."','".$user_id."')";
	mysql_query($sql,$user->Con1);
 }

//---------------------------------------------------------------  
//把新建的目录存储的路径插入到tb_file_location-----
//根据serverid取得文件所存放的文件服务器的ip
 $sql="select * from dataserverid where serverid='".$serverid."'";
  $res=mysql_query($sql,$user->Con1);
 $menu1=mysql_fetch_array($res);
$sql="insert into tb_file_location values('".$id."','".$menu1["serverip"]."','".$url."','".$replicaip."','".$replicapath."','1')";//目录不需要复制或迁移，所以最后一个字段置"1"
  mysql_query($sql,$user->Con1);
setcookie("username",$owner,time()+3600,"/");
//-------------------------------------------------
//记录日志---------------------------------------------
 if(!is_file("/var/log/csc/data_log.txt"))
{
 $fp=fopen("/var/log/csc/data_log.txt","w");
  fclose($fp);
}
$fp = fopen("/var/log/csc/data_log.txt","a");
	fwrite($fp,date("Y-m-d H:i:s")."		");
	fwrite($fp,"newdir		");
	fwrite($fp,$id."	");
	fwrite($fp,$dirname."		");
	fwrite($fp,$owner."\n");
	fclose($fp);
//------------------------------------------------------

?>