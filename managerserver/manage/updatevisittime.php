<?php
$id=$_POST["fileid"];
$visittime=$_POST["visittime"];

require("../include/comment.php");
require("../include/user.class.php");
require_once("cachecount.php");
$userr =&username::getInstance();
//����tb_file_all
$sql="update tb_file_all set visittime='".$visittime."' where id='".$id."'";
mysql_query($sql,$userr->Con1);

$sql="select * from tb_file_all where id='".$id."'";
$result=mysql_query($sql,$userr->Con1);
$array=mysql_fetch_array($result);
$name=$array["name"];
$user_id=$array["user_id"];
$parent_id=$array["parent_id"];

//��ȡ��Ŀ¼��
$sql="select * from tb_file_all where id='".$id."'";
$result=mysql_query($sql,$userr->Con1);
$parent_array=mysql_fetch_array($result);
$parent_name=$parent_array["name"];
$user_id=$parent_array["user_id"];
if($parent_id==0)
{
	$parent_name='/';
}
//��tb_file_cache���в���----------------------------------------------------------------------
//ѡȡ���е�ͬ�汾�ļ�׼������tb_file_cache����ÿ����¼����Ҫ����tb_file_cache����û�У�����ʱ�ռ乻����
$sql="select * from tb_file_all where name='".$name."' and parent_id='".$parent_id."' and user_id='".$user_id."'";

$res=mysql_query($sql,$userr->Con1);
$same_nums=mysql_num_rows($res);
for($i=0;$i<$same_nums;$i++)
{
	$menu=mysql_fetch_array($res);
	//��tb_file_cache����û�иü�¼
	$sql="select * from tb_file_cache where id='".$menu["id"]."'";
	$result=mysql_query($sql,$userr->Con1);
	if(mysql_num_rows($result)>0)
	{//˵��tb_file_cache�к��и��ļ���¼������ִ�и��²���
		$sql="update tb_file_cache set modifytime='".$visittime."' where id='".$id."'";
		
		mysql_query($sql,$userr->Con1);
	}
	else{//˵��û�У���Ҫ����
		$order_num=caculate_ordercount();
		//����cache���ʵ������
		$real_num=caculate_realcount();
		if(($order_num-$real_num)>=1)//�жϿռ乻����
		{//����
			$sql="insert into tb_file_cache values('".$menu["id"]."','".$menu["parent_id"]."','".$menu["name"]."','".$parent_name."','".$menu["version"]."','".$menu["size"]."','".$menu["filetype"]."','".$visittime."','".$user_id."')";
			mysql_query($sql,$userr->Con1);
		}
		else{
			//��ɾ��
			$sql="delete from tb_file_cache order by modifytime limit 1";
			mysql_query($sql,$userr->Con1);
			//�ٲ���
			$sql="insert into tb_file_cache values('".$menu["id"]."','".$menu["parent_id"]."','".$menu["name"]."','".$parent_name."','".$menu["version"]."','".$menu["size"]."','".$menu["filetype"]."','".$visittime."','".$user_id."')";
			mysql_query($sql,$userr->Con1);
		}
	
	}
}

?>