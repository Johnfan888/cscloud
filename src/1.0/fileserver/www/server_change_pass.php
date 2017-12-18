<?php   
require("configure_class.php");
$c = new Configuration();
$c->_construct();
$webserverip=$c->_get("ManagerServerIP");
$newpass=$_GET['password'];
$stage=$_GET['stage'];
$fp=fopen("/var/log/csc/pass.txt","w");
fwrite($fp,$newpass);
fclose($fp);

$stage=$stage+1;
//header("Location:http://".$webserverip."/manage/transfer.php?password=".rawurlencode($_GET['password'])."&stage=".$stage);
$get_url = "http://".$webserverip."/manage/transfer.php?password=".rawurlencode($_GET['password'])."&stage=".$stage ;
		$ch = curl_init($get_url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 

			if ($output===FALSE) {//出错处理,写入错误日志中
			if(!is_file("/var/log/csc/error_log.txt.txt"))
			{
			 $fp=fopen("/var/log/csc/error_log.txt","w");
			  fclose($fp);
			}
			 $fp=fopen("/var/log/csc/error_log.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		");
			   fwrite($fp,"transfer  ");
			  fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			 $flag="0";
			 // break;
			  } 
			   curl_close($ch);
?>