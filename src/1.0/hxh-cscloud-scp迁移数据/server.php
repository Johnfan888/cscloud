<?php
$fp=fopen("/var/log/csc/pass.txt","r");
$password=fread($fp,filesize("/var/log/csc/pass.txt"));
require("configure_class.php");
$c = new Configuration();
$c->_construct();
$userPath= $c->_get("UserFilePath"); //获得地址

$user=$_POST["user"]; //用户id
$check=$_POST["check"]; //密码
$originip=$_POST["originip"]; //添加目标端的ip

if($check==$password)//验证码正确的话，允许文件传输
{
		$fp=fopen("/var/log/csc/receivefileinfo.txt","a");
		/*fwrite($fp,$_FILES['upload']['name']."\n");
		fwrite($fp,$_FILES['upload']['type']."\n");
		fwrite($fp,$_FILES['upload']['size']."\n");
		fwrite($fp,$_FILES['upload']['tmp_name']."\n");
		fwrite($fp,$_FILES['upload']['error']."\n");*/
		
		//最后一层为用户id
		/*$oldpath=$_POST["oldpath"];
		$array=explode("/",$oldpath,3);*/ 
		//fwrite($fp,$user."\n");
		fwrite($fp,$originip."\n");
		$newpath=$userPath.$user."/";
		
		fwrite($fp,$newpath."\n");
		fclose($fp);
		//$dirname=dirname($newpath); //返回目录部分
		//逐级新建目录
		//$arr=file("/var/log/csc/receivefileinfo.txt");
		//$i=count($arr)-1;
		$array=explode("/",$newpath);
		$dir="/";
		for($i=1;$i<count($array);$i++)
		{
			$dir=$dir.$array[$i]."/";
			
			if(!is_dir($dir))
			{
			mkdir($dir,0777);
			}
		}
		
		//目录新建结束
		
		
	/*	echo $_FILES['upload']['name']."\n";//返回文件id
		echo $newpath."\n";//返回文件新路径
		
		if(move_uploaded_file($_FILES['upload']['tmp_name'],$newpath))
		{
			echo "1\n";
			
		 }
		 else
		 {
			echo "0\n";
		 }*/
}
else{//验证码错误
	
echo "非法操作！(您的密码是错误的)";
}
?>
