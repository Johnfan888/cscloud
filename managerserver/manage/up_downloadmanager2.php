<?php 
require("../include/comment.php");
require("../include/user.class.php");

$userr =&username::getInstance();
$user=$_GET['user'];


//�洢�ļ���������ip����
$sql="select * from dataserverid order by serverid";
$res=mysql_query($sql,$userr->Con1);
$nums=mysql_num_rows($res);
$iparray=array();//��$iparray��Ӧ�Ĵ洢�ļ���Ŀ¼
$filepath=array();//�洢�û��ļ���·��
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
//�������ݿ����ǲ������û����ļ�
$sql="select * from tb_file_all where user_id='".$user_id."'";
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
		$replicaip=$menuip["replicaip"];
		$replicapath=dirname($menuip["replicalocation"])."/";
		$manageserverip=$_SERVER['SERVER_ADDR'];
		echo $menuip['serverip']."&".$replicaip."&".$replicapath."&totalsize=".$totalsize."&usedsize=".$usedsize."&".$userfilepath."&".$manageserverip;
}
else//˵���û��ǵ�һ���ϴ��ļ����ո��ؾ���Ĳ���ѡһ̨�ļ�����������
{
		require_once("select_fileserver.php");
		selectserver2($iparray,$filepath,$servernum,$user,$totalsize,$usedsize);
}



?>
