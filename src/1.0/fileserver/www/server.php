<?php
$fp=fopen("/var/log/csc/pass.txt","r");
$password=fread($fp,filesize("/var/log/csc/pass.txt"));
require("configure_class.php");
$c = new Configuration();
$c->_construct();
$userPath= $c->_get("UserFilePath");

$user=$_POST["user"];
$check=$_POST["check"];

/*if(!file_exists("/var/log/csc/receivefileinfo.txt"))
{*/
	$fp=fopen("/var/log/csc/receivefileinfo.txt","w");
	fclose($fp);
/*}*/

if($check==$password)//��֤����ȷ�Ļ��������ļ�����
{
		$fp=fopen("/var/log/csc/receivefileinfo.txt","a");
		fwrite($fp,$_FILES['upload']['name']."\n");
		fwrite($fp,$_FILES['upload']['type']."\n");
		fwrite($fp,$_FILES['upload']['size']."\n");
		fwrite($fp,$_FILES['upload']['tmp_name']."\n");
		fwrite($fp,$_FILES['upload']['error']."\n");
		
		$PublicPath=$_POST['PublicPath'];
		$array=explode("/",$PublicPath);
		$con=count($array);
		$oldpath=$_POST["oldpath"];
		$array=explode("/",$oldpath,$con);
		$con=$con-1;
		$newpath=$userPath.$array[$con];

		
		fwrite($fp,$newpath."\n");
		fclose($fp);
		$dirname=dirname($newpath);
		//���½�Ŀ¼
		$array=explode("/",$dirname);
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
		
		
		echo $_FILES['upload']['name']."\n";//�����ļ�id
		echo $newpath."\n";//�����ļ���·��
		
		if(move_uploaded_file($_FILES['upload']['tmp_name'],$newpath))
		{
			echo "1\n";
			
		 }
		 else
		 {
			echo "0\n";
		 }
}
else{//��֤�����
echo "�Ƿ�������(���������Ǵ����)";

}
?>
