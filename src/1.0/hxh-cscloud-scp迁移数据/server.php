<?php
$fp=fopen("/var/log/csc/pass.txt","r");
$password=fread($fp,filesize("/var/log/csc/pass.txt"));
require("configure_class.php");
$c = new Configuration();
$c->_construct();
$userPath= $c->_get("UserFilePath"); //��õ�ַ

$user=$_POST["user"]; //�û�id
$check=$_POST["check"]; //����
$originip=$_POST["originip"]; //���Ŀ��˵�ip

if($check==$password)//��֤����ȷ�Ļ��������ļ�����
{
		$fp=fopen("/var/log/csc/receivefileinfo.txt","a");
		/*fwrite($fp,$_FILES['upload']['name']."\n");
		fwrite($fp,$_FILES['upload']['type']."\n");
		fwrite($fp,$_FILES['upload']['size']."\n");
		fwrite($fp,$_FILES['upload']['tmp_name']."\n");
		fwrite($fp,$_FILES['upload']['error']."\n");*/
		
		//���һ��Ϊ�û�id
		/*$oldpath=$_POST["oldpath"];
		$array=explode("/",$oldpath,3);*/ 
		//fwrite($fp,$user."\n");
		fwrite($fp,$originip."\n");
		$newpath=$userPath.$user."/";
		
		fwrite($fp,$newpath."\n");
		fclose($fp);
		//$dirname=dirname($newpath); //����Ŀ¼����
		//���½�Ŀ¼
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
		
		//Ŀ¼�½�����
		
		
	/*	echo $_FILES['upload']['name']."\n";//�����ļ�id
		echo $newpath."\n";//�����ļ���·��
		
		if(move_uploaded_file($_FILES['upload']['tmp_name'],$newpath))
		{
			echo "1\n";
			
		 }
		 else
		 {
			echo "0\n";
		 }*/
}
else{//��֤�����
	
echo "�Ƿ�������(���������Ǵ����)";
}
?>
