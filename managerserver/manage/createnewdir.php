<?php 
$dirname=$_POST['dirname'];
//$file=$_GET['file'];
$user=$_COOKIE['admin']['username'];
$parent_id=$_GET["parent_id"];

require("../include/comment.php");
require("../include/user.class.php");
$userr =&username::getInstance();

//�ж��û����Ƿ�Ϊ��
if($dirname=="")
{
/*	echo"<script language='javascript'>if(confirm('Ŀ¼������Ϊ�գ��뷵��')){location.href='updown.php';}else{location.href='updown.php';}</script>"*/;
echo("<script language='javascript'>alert('Ŀ¼������Ϊ�գ���������д��');history.go(-1);</script>");


}
else{

	//��ʹ���û�����members�в�ѯ�û�user_id
	$sql="select user_id from members where username='".$user."'";
	$RES=mysql_query($sql,$userr->Con1);
	$MENU=mysql_fetch_array($RES);
	$user_id=$MENU["user_id"];	
	
	//���ͬ��Ŀ¼
	$sql="select * from tb_file_all where name='".$dirname."' and filetype='1' and user_id='".$user_id."' and parent_id='".$parent_id."'";
	
	$rs=mysql_query($sql,$userr->Con1);
	if(mysql_num_rows($rs)>0)
	{
		echo"<script language='javascript'>if(confirm('���ܶ���ͬ����Ŀ¼���뷵��')){location.href='updown.php';}else{location.href='updown.php';}</script>";
	
	}
	else{
	//�����Ŀ¼�ľ���·��
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
			
			//�������е��ļ�������ip------------------------------------------
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
				
				
			//�������ݿ����ǲ������û����ļ�----------------------------------
			
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
			   
			  //��ȡ�����ļ���������ip�ʹ洢·��
			   $replicaip=$menuip["replicaip"];
			   $sql="select * from dataserverid where serverip='".$replicaip."'";
			   $serres=mysql_query($sql,$userr->Con1);
			   $serme=mysql_fetch_array($serres);
			   $replicapath=$serme["userfilepath"];
			   
			header("location:http://".$menuip['serverip']."/www/index.php?dir=".$dirname."&username=".$user."&flag=newdir"."&parent_id=".$_GET["parent_id"]."&dirpath=".$dirpath."&replicaip=".$replicaip."&replicapath=".$replicapath."&userfilepath=".$userfilepath."&fileserverid=".$fileserverid."&manageserverip=".$_SERVER['SERVER_ADDR']); 
			
			}
				else//˵���û��ǵ�һ���ϴ��ļ����ո��ؾ���Ĳ���ѡһ̨�ļ�����������
			{
			
				require_once("select_fileserver.php");
				selectserver($iparray,$filepath,$servernum,$user,$dirpath,$dirname,$parent_id);
			 
			}
	}
}
?>
