<?php
$id=$_POST["fileid"];
$visittime=$_POST["visittime"];

require("../include/comment.php");
require("../include/user.class.php");
require_once("cachecount.php");
$userr =&username::getInstance();
//更新tb_file_all
$sql="update tb_file_all set visittime='".$visittime."' where id='".$id."'";
mysql_query($sql,$userr->Con1);

$sql="select * from tb_file_all where id='".$id."'";
$result=mysql_query($sql,$userr->Con1);
$array=mysql_fetch_array($result);
$name=$array["name"];
$user_id=$array["user_id"];
$parent_id=$array["parent_id"];

//获取父目录名
$sql="select * from tb_file_all where id='".$id."'";
$result=mysql_query($sql,$userr->Con1);
$parent_array=mysql_fetch_array($result);
$parent_name=$parent_array["name"];
$user_id=$parent_array["user_id"];
if($parent_id==0)
{
	$parent_name='/';
}
//对tb_file_cache进行操作----------------------------------------------------------------------
//选取所有的同版本文件准备插入tb_file_cache，对每条记录都需要考虑tb_file_cache中有没有，插入时空间够不够
$sql="select * from tb_file_all where name='".$name."' and parent_id='".$parent_id."' and user_id='".$user_id."'";

$res=mysql_query($sql,$userr->Con1);
$same_nums=mysql_num_rows($res);
for($i=0;$i<$same_nums;$i++)
{
	$menu=mysql_fetch_array($res);
	//看tb_file_cache中有没有该记录
	$sql="select * from tb_file_cache where id='".$menu["id"]."'";
	$result=mysql_query($sql,$userr->Con1);
	if(mysql_num_rows($result)>0)
	{//说明tb_file_cache中含有该文件记录，所以执行更新操作
		$sql="update tb_file_cache set modifytime='".$visittime."' where id='".$id."'";
		
		mysql_query($sql,$userr->Con1);
	}
	else{//说明没有，需要插入
		$order_num=caculate_ordercount();
		//计算cache表的实际容量
		$real_num=caculate_realcount();
		if(($order_num-$real_num)>=1)//判断空间够不够
		{//够了
			$sql="insert into tb_file_cache values('".$menu["id"]."','".$menu["parent_id"]."','".$menu["name"]."','".$parent_name."','".$menu["version"]."','".$menu["size"]."','".$menu["filetype"]."','".$visittime."','".$user_id."')";
			mysql_query($sql,$userr->Con1);
		}
		else{
			//先删除
			$sql="delete from tb_file_cache order by modifytime limit 1";
			mysql_query($sql,$userr->Con1);
			//再插入
			$sql="insert into tb_file_cache values('".$menu["id"]."','".$menu["parent_id"]."','".$menu["name"]."','".$parent_name."','".$menu["version"]."','".$menu["size"]."','".$menu["filetype"]."','".$visittime."','".$user_id."')";
			mysql_query($sql,$userr->Con1);
		}
	
	}
}

?>