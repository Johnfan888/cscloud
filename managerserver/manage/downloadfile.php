<?php
$dir=$_GET['dir'];
$id=$_GET['id'];
$name=$_GET['name'];
$serverip=$_GET['serverip'];
$user=$_COOKIE['admin']['username'];
$replicaip=$_GET['replicaip'];
$replicalocation=$_GET['replicalocation'];

require("../include/comment.php");
require("../include/user.class.php");

$user1 =&username::getInstance();
$sql="select * from tb_file_cache where id='".$id."'";
 $res=mysql_query($sql,$user1->Con1);
 $num=mysql_num_rows($res);
 //echo $num;
//ʹ���û�����members�в�ѯ�û�user_id
$sql="select user_id from members where username='".$user."'";
$RES=mysql_query($sql,$user1->Con1);
$MENU=mysql_fetch_array($RES);
$user_id=$MENU["user_id"];	  

//�ж��û��ļ���һ�������ڵ��ļ��������Ƿ�����
$SQL="select status from dataserverid where serverip='".$serverip."'";  
$RES=mysql_query($SQL,$user1->Con1);
$statusMenu=mysql_fetch_array($RES); 
$status=$statusMenu["status"];
if($status=='0')//Ϊ0˵������������
{


}
else{//˵����һ�汾���ڵ��ļ����������ϣ����û�����ǣ�����ڶ��������ڵ��ļ���������
$dir=$replicalocation;
$serverip=$replicaip;
}
  
   
   $date=date("Y-m-d H:i:s");
if($num==0)//˵��tb_file_cache��û�������ļ��ļ�¼���û��Ǵ��û������ļ���ߵ�����صģ��Ѹ�����Ϣ����tb_file_cache��
 {	
    //�ж�tb_file_cache�����Ƿ񹻣�������ɾ��һ��
	 $sql="select * from tb_file_cache";
	 if(($order_num-mysql_num_rows(mysql_query($sql,$user1->Con1)))>=1)
	 {
	   $sql="update tb_file_all set visittime='".$date."' where id='".$id."' and user_id='".$user_id."'"; 
		mysql_query($sql,$user1->Con1);
		echo $sql;
	   $sql="insert into tb_file_cache select * from tb_file_all where id='".$id."' and user_id='".$user_id."'"; 
		mysql_query($sql,$user1->Con1);
	 
	 }
	 else{
	    $sql="delete from tb_file_cache order by visittime asc limit 1";
		mysql_query($sql,$user1->Con1);
		//�޸��ļ���Ϣ�ķ���ʱ������
		$sql="update tb_file_all set visittime='".$date."' where id='".$id."' and user_id='".$user_id."'"; 
		mysql_query($sql,$user1->Con1);
		$sql="insert into tb_file_cache select * from tb_file_all where id='".$id."' and user_id='".$user_id."'"; 
		mysql_query($sql,$user1->Con1);
		}
  
 }
 else
 {
       $sql="update tb_file_cache set visittime='".$date."' where id='".$id."' and user_id='".$user_id."'"; 
		mysql_query($sql,$user1->Con1);
		$sql="update tb_file_all set visittime='".$date."' where id='".$id."' and user_id='".$user_id."'"; 
		mysql_query($sql,$user1->Con1);
 
 }
//�鿴�û�Ҫ���ص��ļ��İ汾������ǵ�һ����ôֱ���������أ�����Ǻ���İ汾������Ҫ���ļ��Ȼָ���Ȼ��������
$sql="select * from tb_file_all where id='".$id."' and user_id='".$user_id."'";
$rerere=mysql_query($sql,$user1->Con1);
$meme=mysql_fetch_array($rerere);
if($meme["version"]=='1')
{//ֱ��ȥ����
header("location:http://".$serverip."/www/getfile.php?dir=".$dir."&id=".$id."&name=".$name);
}
else
{
$sql="select * from tb_file_all where name='".$meme["name"]."' and user_id='".$user_id."' and version='1'";
$rerere1=mysql_query($sql,$user1->Con1);
$meme1=mysql_fetch_array($rerere1);
header("location:http://".$serverip."/www/getfile.php?dir=".$dir."&id=".$id."&name=".$name."&oldid=".$meme1["id"]);

}




?>
