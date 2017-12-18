<?php
$originip=$_GET["originip"]; //迁移的元数据服务器
$targetip=$_GET["targetip"]; //迁移的目标服务器
$userid=$_GET["userid"]; //迁移的目标用户
$configmanagerip=$_GET["configmanagerip"]; 
$fp=fopen("/var/log/csc/transfer_server.txt","w");
fwrite($fp,"originip=".$originip."\n");
fwrite($fp,"targetip=".$targetip."\n");
fwrite($fp,"configmanagerip=".$configmanagerip."\n");
fclose($fp);
$get_url="http://localhost/manage/transfer.php?stage=1&id=1&userid=".$userid;
		$ch = curl_init($get_url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ;  //执行时具有一个返回值 
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



 ?>