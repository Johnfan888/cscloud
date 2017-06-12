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
//更新web上个dataserver上用户目录的大小
//记录日志---------------------------------------------
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
//查询Username的user_id
$SQL="select user_id from members where username='".$owner."'";
$RESULT=mysql_query($SQL,$user->Con1);
$MENU=mysql_fetch_array($RESULT);
$user_id=$MENU["user_id"];

//------------------------------------------------------
//首先确定文件的版本号***************************************************************************
$sql="select * from tb_file_all where name='".$filename."' and  user_id='".$user_id."' and parent_id='".$parent_id."' group by version desc limit 1";
$re_v=mysql_query($sql,$user->Con1);

if(mysql_num_rows($re_v)==0)//说明还没有同名文件
{
	$version=1;
}
else//说明已有别的版本
{
	$menu_v=mysql_fetch_array($re_v);
	$version=$menu_v[version]+1;

}

//对tb_file_all表进行操作-----------------------------------------------------------------------------
$sql="insert into tb_file_all values('".$id."','".$parent_id."','".$filename."','".$version."','".$filesize."','".$type."','".$createtime."','".$visittime."','".$modifytime."','".$user_id."')";
mysql_query($sql,$user->Con1);
//对tb_file_all表操作结束-----------------------------------------------------------------------------
//对cache表开始操作----------------------------------------------------------------------------------//获取该文件的上级目录名
$sql="select name from tb_file_all where id='".$parent_id."'";
$res=mysql_query($sql,$user->Con1);
$menu=mysql_fetch_array($res);
$parent_name=$menu["name"];

if($parent_id==0)
{
	$parent_name='/';
}
//选取所有的同版本文件准备插入tb_file_cache，对每条记录都需要考虑tb_file_cache中有没有，插入时空间够不够
$sql="select * from tb_file_all where name='".$filename."' and parent_id='".$parent_id."' and user_id='".$user_id."'";
$res=mysql_query($sql,$user->Con1);
$same_nums=mysql_num_rows($res);
for($i=0;$i<$same_nums;$i++)
{
	$menu=mysql_fetch_array($res);
	//看tb_file_cache中有没有该记录
	$sql="select * from tb_file_cache where id='".$menu["id"]."'";
	$result=mysql_query($sql,$user->Con1);
	if(mysql_num_rows($result)>0)
	{//说明tb_file_cache中含有该文件记录，所以执行更新操作
		$sql="update tb_file_cahce set modifytime='".$visittime."' where id='".$id."'";
		mysql_query($sql,$user->Con1);
	}
	else{//说明没有，需要插入
		$order_num=caculate_ordercount();
		//计算cache表的实际容量
		$real_num=caculate_realcount();
		if(($order_num-$real_num)>=1)//判断空间够不够
		{//够了
			$sql="insert into tb_file_cache values('".$menu["id"]."','".$menu["parent_id"]."','".$menu["name"]."','".$parent_name."','".$menu["version"]."','".$menu["size"]."','".$menu["filetype"]."','".$visittime."','".$user_id."')";
			mysql_query($sql,$user->Con1);
		}
		else{
			//先删除
			$sql="delete from tb_file_cache order by modifytime limit 1";
			mysql_query($sql,$user->Con1);
			//再插入
			$sql="insert into tb_file_cache values('".$menu["id"]."','".$menu["parent_id"]."','".$menu["name"]."','".$parent_name."','".$menu["version"]."','".$menu["size"]."','".$menu["filetype"]."','".$visittime."','".$user_id."')";
			mysql_query($sql,$user->Con1);
		}
	
	}
}
//对cache表操作结束-------------------------------------------------------------------------------

 //根据serverid取得文件所存放的文件服务器的ip
	$sql="select * from dataserverid where serverid='".$serverid."'";
	$res=mysql_query($sql,$user->Con1);
	$menu1=mysql_fetch_array($res);
//对tb_file_location表进行插入操作----------------------------------------------------------------
	$sql="insert into tb_file_location values('".$id."','".$menu1["serverip"]."','".$url."','".$replicaip."','".$replicapath."','0')";
	mysql_query($sql,$user->Con1);
  
//修改filesize表-----------------------------------------------------------------------------------
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

//插入数据库完毕***********************************************************************
if($version!=1)
{
//取得版本1的serverip
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
		if ($output===FALSE) {//出错处理,写入错误日志中
		$fp=fopen("/var/log/csc/backup_error.txt","w");
		fwrite($fp,date("Y-m-d H:i:s")."		");
		fwrite($fp,$url."\n");
		fwrite($fp,"cURL Error:".curl_error($ch)."   ");
		fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
		} 
		curl_close($ch);
}
?>