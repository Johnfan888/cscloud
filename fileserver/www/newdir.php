<?php
require("configure_class.php");
$c = new Configuration();
$c->_construct();
$webserverip=trim($c->_get("ManagerServerIP"));
$serverid=trim($c->_get("ServerID"));
$userPath= trim($c->_get("UserFilePath"));
$user =$_GET['owner'];
$dirname=$_GET["dir"];
$dir=$userPath.$_GET['owner'].$_GET["dirpath"];

//--------------------------------------------------------------------------------------------
function randStr($len=6) { 
$chars='0123456789'; // characters to build the password from 
mt_srand((double)microtime()*1000000*getmypid()); // seed the random number generater (must be done) 
$password=''; 
while(strlen($password)<$len) 
$password.=substr($chars,(mt_rand()%strlen($chars)),1); 
return $password; 
} 
//--------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Shanghai');
$showtime=date("YmdHis");
$rondstr=randStr(4);
$id=$showtime.$rondstr;
$newdir =$dir.$id;
if(!is_dir($dir))//查看父目录是否存在
{
	mkdir($dir,0777);
}
if(!is_dir($newdir))
{
		mkdir($newdir,0777);//新建目录
		$path=$newdir;
		$filesize=filesize("$newdir");
		$type="1";
		$createtime=date("Y-m-d H:i:s", filectime("$newdir"));
		$visittime=date("Y-m-d H:i:s", fileatime("$newdir"));
		$modifytime=date("Y-m-d H:i:s", filemtime("$newdir"));
		$owner=$_GET['owner'];
	
	
	//记录日志---------------------------------------------
	  if(!is_file("/var/log/csc/data_log.txt"))
			{
			 $fp=fopen("/var/log/csc/data_log.txt","w");
			  fclose($fp);
			}
	   $fp=fopen("/var/log/csc/data_log.txt","a");
	   fwrite($fp,date("Y-m-d H:i:s")."		");
	   fwrite($fp,"newdir		");
	   fwrite($fp,$id."		");
	   fwrite($fp,$dirname."		");
	   fwrite($fp,$_GET['owner']."		");
	   fwrite($fp,$_GET['replicaip']."		");
	   fwrite($fp,$_GET['replicapath']."		");
	   fwrite($fp,$serverid."\n");
	   fclose($fp);		
	//--------------------------------------------



		//把上传文件的信息传递给manageserver，插入数据库
		$post_url = "http://".$webserverip."/manage/insertnewdir.php" ;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $post_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		$post_data= array( 
			"id" => $id,
			"dirname"=>$dirname,
			"url" =>$path,
			"filesize" =>$filesize,
			"type" =>$type,
			"createtime" =>$createtime,
			"parent_id" =>$_GET['parent_id'],
			"visittime" =>$visittime,
			"modifytime" =>$modifytime,
			"owner" =>$_GET['owner'],
			"replicaip" =>$_GET['replicaip'],
			"replicapath" =>$_GET['replicapath'].$_GET['owner'].$_GET["dirpath"].$id,
			"serverid" =>$serverid
		);
		curl_setopt($ch,CURLOPT_VERBOSE,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);
		if ($output===FALSE) {//出错处理,写入错误日志中
			if(!is_file("/var/log/csc/error_log.txt"))
			{
				$fp=fopen("/var/log/csc/error_log.txt","w");
				fclose($fp);
			}
			$fp=fopen("/var/log/csc/error_log.txt","a");
			fwrite($fp,date("Y-m-d H:i:s")."		");
			fwrite($fp,"upload   ");
			fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			$flag="0";
			// break;
		} 
		
  curl_close($ch);

 }
	
	echo "<script language=\"JavaScript\">";
	echo "if(confirm(\"新建成功，请返回\"))";
	echo " { location.href=\"http://".$webserverip."/manage/updown.php\";}";
	echo " else {location.href=\"http://".$webserverip."/manage/updown.php\";}";
	echo "</script>";
?>

