<?php
/*echo "<script language='javascript'>
window.setTimeout('location.reload()',30);
</script>";*/



require("conn/conn.php");
require 'configure_class.php';
require_once('/srv/www/htdocs/configmanager/sendmail/socketsendmail.php');
$flag=$_GET["flag"];
if($flag==1)//将ip信息发给ManageServer
{

$sql="select * from ip_table where status='manager'";
$result=mysql_query($sql,$conne->conn);
$menu=mysql_fetch_array($result);
$webserverip=trim($menu["ip_address"]); //manager的ip

$fp=fopen("transfer_ip.txt","r");
$originip=trim(fgets($fp)); //源数据服务器
$targetip=trim(fgets($fp)); //目标数据服务器
$userid=trim(fgets($fp)); //用户id号
fclose($fp);


//修改数据库，表明有文件服务器正在迁移
$sql="update QUEUE set STATUS='1' where QUEUENAME='transfer'";
mysql_query($sql,$conne->conn);
//修改ip_table的transfer_status字段，
$sql="update ip_table set transfer_status='1' where ip_address='".$originip."'";
mysql_query($sql,$conne->conn);

//获取本地ip
		$ip=exec("/bin/sh getlocalip.sh"); //获得ip
		//$array=explode("/",$ip);
		//$localip=$array[0];
		$localserverip=trim($ip,'"');
		//$localserverip=trim($localip,"\'");
		$get_url="http://".$webserverip."/manage/receive_transfer_ip.php?originip=".$originip."&targetip=".$targetip."&configmanagerip=".$localserverip."&userid=".$userid;
		$ch = curl_init($get_url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 
			if ($output===FALSE) {//出错处理,写入错误日志中
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
					$flag=fwrite($fp,'1'); //迁移完成后
					fclose($fp);
					 $fp=fopen("transfer_ip.txt","r");
					 $originip=trim(fgets($fp));
					 $targetip=trim(fgets($fp));
					 fclose($fp);
					 
				//给管理员发邮件
						$mail=new mail();
					    $data='源数据服务器'.$originip.'：向'.$targetip.'：迁移用户'.$userid.'的数据成功';
						$mail->send("负载迁移",$data);
						$time=date("Y-m-d H:i:s");
					 	$sql="insert into log_transfer(source_ip,target_ip,time) values('".$originip."','".$targetip."','".$time."')"; //插入到迁移日志中
						mysql_query($sql,$conne->conn);
					echo "<script language='javascript'>
		  			 if(confirm('迁移完成'))
		       		 {
		             //如果是true，
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