<?php
require("conn.php");

		$fp=fopen("/var/log/csc/replica_file_info.txt","a");
		fwrite($fp,$_FILES['upload']['name']."\n");
		fwrite($fp,$_FILES['upload']['type']."\n");
		fwrite($fp,$_FILES['upload']['size']."\n");
		fwrite($fp,$_FILES['upload']['tmp_name']."\n");
		fwrite($fp,$_FILES['upload']['error']."\n");
		fclose($fp);
		
		$newpath=$_POST["path"];


		$dirname=dirname($newpath);
		$array=explode("/",$dirname);
		$length=count($array);
		//echo $length-1;
		$dir="/";
		for($i=1;$i<$length;$i++)
		{
			$dir=$dir.$array[$i]."/";
			if(!is_dir($dir))
			{
			mkdir($dir,0777);
			}
		}

		//echo $_FILES['upload']['name']."\n";
		//echo $newpath."\n";
		
		if(move_uploaded_file($_FILES['upload']['tmp_name'],$newpath)){
			echo "1";
			
		 }
		 else
		 {
			echo "0";
		 }
/*}
else{//验证码错误
echo "非法操作！(您的密码是错误的)";

}*/
?>
