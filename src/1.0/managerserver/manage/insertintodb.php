<?php 
require("../include/comment.php");
require("../include/user.class.php");
require("./configure_class.php");
require("./cachecount.php");
$c = new Configuration();
$c->_construct();
$user =&username::getInstance();
$id=$_POST['id'];
$filename=$_POST['filename'];
$url=$_POST['url'];
$filesize=$_POST['filesize'];
$type=$_POST['type'];
$createtime=$_POST['createtime'];
$visittime=$_POST['visittime'];
$modifytime=$_POST['modifytime'];
$owner=$_POST['owner'];
$userDirSize=$_POST['userDirSize'];
$serverid=$_POST['serverid'];
$parent_id=$_POST['parent_id'];
$replicaip=$_POST['replicaip'];
$replicapath=$_POST['replicapath'];
//����web�ϸ�dataserver���û�Ŀ¼�Ĵ�С
//��¼��־---------------------------------------------
 if(!is_file("/var/log/csc/data_log.txt"))
{
	$fp=fopen("/var/log/csc/data_log.txt","w");
	fclose($fp);
}


	$fp = fopen("/var/log/csc/data_log.txt","a");
	fwrite($fp,date("Y-m-d H:i:s")."		");
	fwrite($fp,"upload		");
	fwrite($fp,$id."	");
	fwrite($fp,$filename."		");
	fwrite($fp,$owner."\n");
	fclose($fp);
//��ѯUsername��user_id
$SQL="select user_id from members where username='".$owner."'";
$RESULT=mysql_query($SQL,$user->Con1);
$MENU=mysql_fetch_array($RESULT);
$user_id=$MENU["user_id"];

//------------------------------------------------------
//����ȷ���ļ��İ汾��***************************************************************************
$sql="select * from tb_file_all where name='".$filename."' and  user_id='".$user_id."' and parent_id='".$parent_id."' group by version desc limit 1";
$re_v=mysql_query($sql,$user->Con1);

if(mysql_num_rows($re_v)==0)//˵����û��ͬ���ļ�
{
	$version=1;
}
else//˵�����б�İ汾
{
	$menu_v=mysql_fetch_array($re_v);
	$version=$menu_v[version]+1;

}

//��tb_file_all����в���-----------------------------------------------------------------------------
$sql="insert into tb_file_all values('".$id."','".$parent_id."','".$filename."','".$version."','".$filesize."','".$type."','".$createtime."','".$visittime."','".$modifytime."','".$user_id."')";
mysql_query($sql,$user->Con1);
//��tb_file_all���������-----------------------------------------------------------------------------
//��cache��ʼ����----------------------------------------------------------------------------------//��ȡ���ļ����ϼ�Ŀ¼��
$sql="select name from tb_file_all where id='".$parent_id."'";
$res=mysql_query($sql,$user->Con1);
$menu=mysql_fetch_array($res);
$parent_name=$menu["name"];

if($parent_id==0)
{
	$parent_name='/';
}
//ѡȡ���е�ͬ�汾�ļ�׼������tb_file_cache����ÿ����¼����Ҫ����tb_file_cache����û�У�����ʱ�ռ乻����
$sql="select * from tb_file_all where name='".$filename."' and parent_id='".$parent_id."' and user_id='".$user_id."'";
$res=mysql_query($sql,$user->Con1);
$same_nums=mysql_num_rows($res);
for($i=0;$i<$same_nums;$i++)
{
	$menu=mysql_fetch_array($res);
	//��tb_file_cache����û�иü�¼
	$sql="select * from tb_file_cache where id='".$menu["id"]."'";
	$result=mysql_query($sql,$user->Con1);
	if(mysql_num_rows($result)>0)
	{//˵��tb_file_cache�к��и��ļ���¼������ִ�и��²���
		$sql="update tb_file_cahce set modifytime='".$visittime."' where id='".$id."'";
		mysql_query($sql,$user->Con1);
	}
	else{//˵��û�У���Ҫ����
		$order_num=caculate_ordercount();
		//����cache���ʵ������
		$real_num=caculate_realcount();
		if(($order_num-$real_num)>=1)//�жϿռ乻����
		{//����
			$sql="insert into tb_file_cache values('".$menu["id"]."','".$menu["parent_id"]."','".$menu["name"]."','".$parent_name."','".$menu["version"]."','".$menu["size"]."','".$menu["filetype"]."','".$visittime."','".$user_id."')";
			mysql_query($sql,$user->Con1);
		}
		else{
			//��ɾ��
			$sql="delete from tb_file_cache order by modifytime limit 1";
			mysql_query($sql,$user->Con1);
			//�ٲ���
			$sql="insert into tb_file_cache values('".$menu["id"]."','".$menu["parent_id"]."','".$menu["name"]."','".$parent_name."','".$menu["version"]."','".$menu["size"]."','".$menu["filetype"]."','".$visittime."','".$user_id."')";
			mysql_query($sql,$user->Con1);
		}
	
	}
}
//��cache���������-------------------------------------------------------------------------------

 //����serveridȡ���ļ�����ŵ��ļ���������ip
	$sql="select * from dataserverid where serverid='".$serverid."'";
	$res=mysql_query($sql,$user->Con1);
	$menu1=mysql_fetch_array($res);
//��tb_file_location����в������----------------------------------------------------------------
	$sql="insert into tb_file_location values('".$id."','".$menu1["serverip"]."','".$url."','".$replicaip."','".$replicapath."','0')";
	mysql_query($sql,$user->Con1);
  
//�޸�filesize��-----------------------------------------------------------------------------------
	mysql_query("set names gbk");
	$sql="select * from filesize where username='".$owner."'";
	$result=mysql_query($sql,$user->Con1);
	if(mysql_num_rows($result)>0)
	{
		$array=mysql_fetch_array($result);
		if($array["serverip"]==$menu1["serverip"])
		{$sql="update filesize set usedsize='".$userDirSize."' where username='".$owner."'";
		mysql_query($sql,$user->Con1);}
		else{
		$sql="update filesize set usedsize='".$userDirSize."',serverip='".$menu1["serverip"]."' where username='".$owner."'";
		mysql_query($sql,$user->Con1);
		}
	}
	else{
		$sql="insert into filesize(serverip,username,usedsize) values('".$menu1["serverip"]."','".$owner."','".$userDirSize."')";
		mysql_query($sql,$user->Con1);
	}

//�������ݿ����***********************************************************************
if($version!=1)
{
//ȡ�ð汾1��serverip
	//$sql="select * from tb_file_location where id =(select id from tb_file_all where name='".$filename."' and  fileowner='".$owner."' and version='1')";
		$sql="select * from tb_file_location where id =(select id from tb_file_all where name='".$filename."' and  user_id='".$user_id."' and version='1')";
		$re1=mysql_query($sql,$user->Con1);
		$m1=mysql_fetch_array($re1);
		
		$urll=$m1["serverip"]."/www/backup.php?";
		$url="http://".$urll."path=".$url."&oldid=".$m1["id"]."&newid=".$id;
		$ch = curl_init($url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 
		if ($output===FALSE) {//������,д�������־��
		$fp=fopen("/var/log/csc/backup_error.txt","w");
		fwrite($fp,date("Y-m-d H:i:s")."		");
		fwrite($fp,$url."\n");
		fwrite($fp,"cURL Error:".curl_error($ch)."   ");
		fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
		} 
		curl_close($ch);
}
?>