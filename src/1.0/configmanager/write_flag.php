<?php 
require("conn/conn.php");
require 'configure_class.php';
require_once('/srv/www/htdocs/configmanager/sendmail/socketsendmail.php');
$sql="update QUEUE set STATUS='0' where QUEUENAME='transfer'";
 mysql_query($sql,$conne->getconnect());
if($_GET["flag"]==3 || $_GET["flag"]==2)
{//˵��������Ŀ�������ֻ�е����û�������Ǩ�ƶ����صģ������ݿ�ع�
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
			$sql="insert into log_transfer(source_ip,target_ip,time) values('".$originip."','".$targetip."','".$time."')"; //���뵽Ǩ����־��
			mysql_query($sql,$conne->conn);
	//������Ա���ʼ�
			$mail=new mail();
			$data='Դ���ݷ�����'.$originip.'����'.$targetip.'��Ǩ�����ݳɹ�';
			$mail->send("����Ǩ��",$data);*/
		 
		

?>