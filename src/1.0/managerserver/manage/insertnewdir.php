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

//���½���Ŀ¼��Ϣ���뵽tb_file_all,tb_file_cache,tb_file_location��---------------------------------
//��ʹ���û�����members�в�ѯ�û�user_id
$sql="select user_id from members where username='".$owner."'";
$RES=mysql_query($sql,$user->Con1);
$MENU=mysql_fetch_array($RES);
$user_id=$MENU["user_id"];	
//���½���Ŀ¼��Ϣ����tb_file_all
$sql="insert into tb_file_all values('".$id."','".$parent_id."','".$name."','','".$filesize."','".$type."','".$createtime."','".$visittime."','".$modifytime."','".$user_id."')";
mysql_query($sql,$user->Con1);

//��ȡ���ļ����ϼ�Ŀ¼��
$sql="select name from tb_file_all where id='".$parent_id."'";
$res=mysql_query($sql,$user->Con1);
$menu=mysql_fetch_array($res);
$dirname=$menu["name"];

//����cache��,����ȷ��tb_file_cache��Ĵ�С
$SQL="select * from tb_file_all";
$res=mysql_query($SQL,$user->Con1);
$num=mysql_num_rows($res);//�鿴tb_file_all���ж�������¼��cacheΪall��1/4
$cachenum=ceil($num/4);
if($cachenum<100)
{
	$order_num=100;//cache��������100����¼
}
else{
	$order_num=$cachenum;
}

//���cache��洢�����Ƿ�ﵽ����
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
else//������ɾ��һ����¼�ٲ���(ɾ���ǽ��޸�ʱ������Ľ���ɾ��)
{
	$sql="delete from tb_file_cache order by modifytime asc limit 1";
	mysql_query($sql,$user->Con1);
	$sql="insert into tb_file_cache values('".$id."','".$parent_id."','".$name."','".$dirname."','1','".$filesize."','".$type."','".$modifytime."','".$user_id."')";
	mysql_query($sql,$user->Con1);
 }

//---------------------------------------------------------------  
//���½���Ŀ¼�洢��·�����뵽tb_file_location-----
//����serveridȡ���ļ�����ŵ��ļ���������ip
 $sql="select * from dataserverid where serverid='".$serverid."'";
  $res=mysql_query($sql,$user->Con1);
 $menu1=mysql_fetch_array($res);
$sql="insert into tb_file_location values('".$id."','".$menu1["serverip"]."','".$url."','".$replicaip."','".$replicapath."','1')";//Ŀ¼����Ҫ���ƻ�Ǩ�ƣ��������һ���ֶ���"1"
  mysql_query($sql,$user->Con1);
setcookie("username",$owner,time()+3600,"/");
//-------------------------------------------------
//��¼��־---------------------------------------------
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