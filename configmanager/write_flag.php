<?php 
require("conn/conn.php");
require 'configure_class.php';
require_once('/srv/www/htdocs/configmanager/sendmail/socketsendmail.php');
$sql="update QUEUE set STATUS='0' where QUEUENAME='transfer'";
 mysql_query($sql,$conne->getconnect());
if($_GET["flag"]==3 || $_GET["flag"]==2)
{//说明是由于目标服务器只有单个用户，不用迁移而返回的，将数据库回滚
$sql="update ip_table set transfer_status='0' where ip_address='".$_GET["ip"]."'";
 mysql_query($sql,$conne->getconnect());
}
		 $fp=fopen("flag.txt","w");
		 $flag=fwrite($fp,'2');
		 fclose($fp);
		/* $fp=fopen("transfer_ip.txt","r");
		 $originip=trim(fgets($fp));
		 $targetip=trim(fgets($fp));
		 fclose($fp);
			$time=date("Y-m-d H:i:s");
			$sql="insert into log_transfer(source_ip,target_ip,time) values('".$originip."','".$targetip."','".$time."')"; //插入到迁移日志中
			mysql_query($sql,$conne->conn);
	//给管理员发邮件
			$mail=new mail();
			$data='源数据服务器'.$originip.'：向'.$targetip.'：迁移数据成功';
			$mail->send("负载迁移",$data);*/
		 
		

?>