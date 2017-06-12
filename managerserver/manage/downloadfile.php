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
//使用用户名在members中查询用户user_id
$sql="select user_id from members where username='".$user."'";
$RES=mysql_query($sql,$user1->Con1);
$MENU=mysql_fetch_array($RES);
$user_id=$MENU["user_id"];	  

//判断用户文件第一副本所在的文件服务器是否正常
$SQL="select status from dataserverid where serverip='".$serverip."'";  
$RES=mysql_query($SQL,$user1->Con1);
$statusMenu=mysql_fetch_array($RES); 
$status=$statusMenu["status"];
if($status=='0')//为0说明服务器正常
{


}
else{//说明第一版本所在的文件服务器故障，把用户请求牵引到第二副本所在的文件服务器上
$dir=$replicalocation;
$serverip=$replicaip;
}
  
   
   $date=date("Y-m-d H:i:s");
if($num==0)//说明tb_file_cache中没有这条文件的记录，用户是从用户所有文件这边点击下载的，把该条信息插入tb_file_cache中
 {	
    //判断tb_file_cache容量是否够，不够先删除一条
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
		//修改文件信息的访问时间属性
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
//查看用户要下载的文件的版本，如果是第一版那么直接让他下载，如果是后面的版本，则需要将文件先恢复，然后在下载
$sql="select * from tb_file_all where id='".$id."' and user_id='".$user_id."'";
$rerere=mysql_query($sql,$user1->Con1);
$meme=mysql_fetch_array($rerere);
if($meme["version"]=='1')
{//直接去下载
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
