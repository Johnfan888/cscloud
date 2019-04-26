<?php
header("content-type:text/html;charset=utf-8");         //设置编码
error_reporting(0);//忽略notice级别的错误提示
require("/srv/www/htdocs/configmanager/conn/conn.php");
require '/srv/www/htdocs/configmanager/configure_class.php';
//require_once('/srv/www/htdocs/configmanager/sendmail/socketsendmail.php');
//$flag=$_GET["flag"];
$flag=1;
if($flag==1)//将ip信息发给ManageServer
{

$sql="select * from ip_table where status='manager'";
$result=mysql_query($sql,$conne->conn);
$menu=mysql_fetch_array($result);
$webserverip=trim($menu["ip_address"]); //manager的ip

$fp=fopen("/srv/www/htdocs/configmanager/transfer_ip.txt","r");
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
//		$ip=exec("/bin/sh /srv/www/htdocs/configmanager/getlocalip.sh"); //获得ip
//		$array=explode("/",$ip);
//		$localip=$array[0];
//		$localserverip=trim($localip,"\'");

		exec("/bin/sh  /srv/www/htdocs/configmanager/getlocalip.sh",$array);
        //echo $array[0];
        $localip=explode("/",$array[0]);
        $localip=$localip[0];
        $localserverip=trim($localip,"\'");


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
			  	 $fp1=fopen("/srv/www/htdocs/configmanager/flag.txt","r");
				 $fileid=fread($fp1,filesize("/srv/www/htdocs/configmanager/flag.txt")); //1
 				 fclose($fp1);
			   //echo "originip".$originip;
			   //echo "<br>targetip".$targetip;
			   //echo "<br>localserverip".$localserverip;
			     
 				if($fileid==2){
 					$fp=fopen("/srv/www/htdocs/configmanager/flag.txt","w");
					$flag=fwrite($fp,'1'); //迁移完成后
					fclose($fp);
					$fp=fopen("/srv/www/htdocs/configmanager/transfer_ip.txt","r");
					$originip=trim(fgets($fp));
					$targetip=trim(fgets($fp));
					fclose($fp);
					echo "From ".$originip." to ".$targetip." transfer successfully!";
 				}
			  
}
