 <?php
/*if(!$_COOKIE['admin']['user_id']){
	echo "δ��½";
	exit();
	
}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�ޱ����ĵ�</title>
<link rel='stylesheet' type='text/css' href='css/private.css'>
</head>

<body>
<?php 
require("../include/comment.php");
require("../include/user.class.php");
$userr =&username::getInstance();
$user=$_GET['user'];

//���õݹ鷨������ļ���dirpath
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
//�洢�ļ���������ip����
$sql="select * from dataserverid order by serverid";
$res=mysql_query($sql,$userr->Con1);
$nums=mysql_num_rows($res);
$iparray=array();
$filepath=array();
  for($rows=0;$rows<$nums;$rows++)             
{             
			  //����ǰ�˵���Ŀ�����ݵ�������             
		         $me=mysql_fetch_array($res);
				 $idarray[$rows]=$me["serverid"];
				 $iparray[$rows]=$me["serverip"];
				 $filepath[$rows]=$me["userfilepath"];
}
$servernum=$nums;//�洢�ļ��������ĸ���

//ʹ���û�����members�в�ѯ�û�user_id
$sql="select user_id from members where username='".$user."'";
$RES=mysql_query($sql,$userr->Con1);
$MENU=mysql_fetch_array($RES);
$user_id=$MENU["user_id"];	

//��ȡ���û���ʹ�õĴ��̿ռ�
$sql="select * from filesize where username='".$user."'";
$result=mysql_query($sql,$userr->Con1);
$array=mysql_fetch_array($result);
$totalsize=$array["totalsize"];
$usedsize=$array["usedsize"];

//�������ݿ����ǲ������û����ļ�
$sql="select * from tb_file_all where user_id='".$user_id."' order by id";
$result=mysql_query($sql,$userr->Con1);
$filenum=mysql_num_rows($result);
if($filenum>0)//˵���û���ǰ�ϴ����ļ�������Ҵ洢�û��ļ���fs��ip
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
		
		//��ȡ�����ļ���������ip�ʹ洢·��
		$replicaip=$menuip["replicaip"];
		$sql="select * from dataserverid where serverip='".$replicaip."'";
		$serres=mysql_query($sql,$userr->Con1);
		$serme=mysql_fetch_array($serres);
		$replicapath=$serme["userfilepath"];
		
		header("location:http://".$menuip['serverip']."/www/index.php?username=".$user."&flag=upload"."&parent_id=".$_GET["parent_id"]."&dirpath=".$dirpath."&replicaip=".$replicaip."&replicapath=".$replicapath."&userfilepath=".$userfilepath."&fileserverid=".$fileserverid."&totalsize=".$totalsize."&usedsize=".$usedsize."&manageserverip=".$_SERVER['SERVER_ADDR']); 


}
else//˵���û��ǵ�һ���ϴ��ļ����ո��ؾ���Ĳ���ѡһ̨�ļ�����������
{

		require_once("select_fileserver.php");
		selectserver1($iparray,$filepath,$servernum,$user,$dirpath,$totalsize,$usedsize);
 
}







?>








</body>
</html>
