<?php
/*echo "<script language='javascript'>
window.setTimeout('location.reload()',30);
</script>";*/



require("conn/conn.php");
require 'configure_class.php';
require_once('/srv/www/htdocs/configmanager/sendmail/socketsendmail.php');
$flag=$_GET["flag"];
if($flag==1)//��ip��Ϣ����ManageServer
{

$sql="select * from ip_table where status='manager'";
$result=mysql_query($sql,$conne->conn);
$menu=mysql_fetch_array($result);
$webserverip=trim($menu["ip_address"]); //manager��ip

$fp=fopen("transfer_ip.txt","r");
$originip=trim(fgets($fp)); //Դ���ݷ�����
$targetip=trim(fgets($fp)); //Ŀ�����ݷ�����
$userid=trim(fgets($fp)); //�û�id��
fclose($fp);


//�޸����ݿ⣬�������ļ�����������Ǩ��
$sql="update QUEUE set STATUS='1' where QUEUENAME='transfer'";
mysql_query($sql,$conne->conn);
//�޸�ip_table��transfer_status�ֶΣ�
$sql="update ip_table set transfer_status='1' where ip_address='".$originip."'";
mysql_query($sql,$conne->conn);

//��ȡ����ip
		$ip=exec("/bin/sh getlocalip.sh"); //���ip
		$array=explode("/",$ip);
		$localip=$array[0];
		$localserverip=trim($localip,"\'");
		$get_url="http://".$webserverip."/manage/receive_transfer_ip.php?originip=".$originip."&targetip=".$targetip."&configmanagerip=".$localserverip."&userid=".$userid;
		$ch = curl_init($get_url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 
			if ($output===FALSE) {//��������,д�������־��
			if(!is_file("/var/log/csc/transfer_log.txt"))
			{
			 $fp=fopen("/var/log/csc/transfer_log.txt","w");
			  fclose($fp);
			}
			 $fp=fopen("/var/log/csc/transfer_log.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		");
			   fwrite($fp,"transfer  ");
			   fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			   fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);
			  	 $fp1=fopen("flag.txt","r");
				 $fileid=fread($fp1,filesize("flag.txt")); //1
 				 fclose($fp1);
			   echo "originip".$originip;
			   echo "<br>targetip".$targetip;
			   echo "<br>localserverip".$localserverip;
			     
 				if($fileid==2){
 					$fp=fopen("flag.txt","w");
					$flag=fwrite($fp,'1'); //Ǩ����ɺ�
					fclose($fp);
					 $fp=fopen("transfer_ip.txt","r");
					 $originip=trim(fgets($fp));
					 $targetip=trim(fgets($fp));
					 fclose($fp);
					 
				//������Ա���ʼ�
						$mail=new mail();
					    $data='Դ���ݷ�����'.$originip.'����'.$targetip.'��Ǩ���û�'.$userid.'�����ݳɹ�';
						$mail->send("����Ǩ��",$data);
						$time=date("Y-m-d H:i:s");
					 	$sql="insert into log_transfer(source_ip,target_ip,time) values('".$originip."','".$targetip."','".$time."')"; //���뵽Ǩ����־��
						mysql_query($sql,$conne->conn);
					echo "<script language='javascript'>
		  			 if(confirm('Ǩ�����'))
		       		 {
		             //�����true��
		           //  location.href='if_need_transfer.php';
					 	window.close();
		         	}
		        	else
		        	{
		             
		          //    location.href='if_need_transfer.php';
					    window.close();
		        	 }
		
		</script>";		
 				}
			  
}





?>