<?php
require("conn.php");
$owner=$_POST['owner'];
$parent_id=$_POST['parent_id'];
$dirpath=$_POST['dirpath'];
$dir=$userPath.$_POST['owner'];

//用随机数产生文件名的函数
function randStr($len=6) { 
$chars='0123456789'; // characters to build the password from 
mt_srand((double)microtime()*1000000*getmypid()); // seed the random number generater (must be done) 
$password=''; 
while(strlen($password)<$len) 
$password.=substr($chars,(mt_rand()%strlen($chars)),1); 
return $password; 
} 
//--------------------------------------------------------------------------------------------
//逐级判断目录是否存在，如果不存在的话，先建立目录
$array=explode("/",$dirpath);
for($i=0;$i<count($array);$i++)
{
	$dir=$dir."/".$array[$i];
	if(!is_dir($dir))
	{
		mkdir($dir,0777);
	}
}

date_default_timezone_set('Asia/Shanghai');
$showtime=date("YmdHis");
$rondstr=randStr(4);
$id=$showtime.$rondstr;

$uploadfile = $dir.'/'.$id;
$filename=$_FILES["upload"]["name"];//取得文件的真实文件名
$pos=strrpos($_FILES["upload"]["name"],"."); //取得文件名中后缀名的开始位置
$ext=substr($filename,$pos);//取得后缀名，包括点号
$url=$uploadfile;
      //  $filesize=$_FILES['Filedata']['size'];
		
		
if(1)
{

//把临时文件以新的名称保存到指定的目录
/*move_uploaded_file($f['tmp_name'],$dir.'/'.$newName_f);*/
		if(move_uploaded_file($_FILES['upload']['tmp_name'],$uploadfile))
		{
			//$owner=$_COOKIE["username"];
			$filesize=filesize("$uploadfile");
			$type=$ext;
			$createtime=date("Y-m-d H:i:s", filectime("$uploadfile"));
			$visittime=date("Y-m-d H:i:s", fileatime("$uploadfile"));
			$modifytime=date("Y-m-d H:i:s", filemtime("$uploadfile")); 
	//将该文件的基本信息打包，然后发送到insertintodb_c.php，将信息插入到数据库中。
		
		$post_data= array( 
		"id"=>$id,
		"filename"=>$filename,
		"url" => $url,
		"filesize"=>$filesize,
		"type"=>$type,
		"createtime"=>$createtime,
		"visittime"=>$visittime,
		"modifytime"=>$modifytime,
		"owner"=>$owner,
		"serverid"=>$serverid,
		"parent_id"=>$parent_id,
		"replicaip"=>$_POST['replicaip'],
		"replicapath"=>$_POST['replicapath'].$dirpath."/".$id);
		
		 $url = "http://".$webserverip."/manage/insertintodb.php" ;
		/*    $ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_VERBOSE,1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$output = curl_exec($ch);
		    curl_close($ch);*/
			$output=send($url,$post_data);
		   echo $output;
		}
		else{echo "error";}
}
else{

setcookie("status","fail",time()+3600,"/");


}
//发送文件基本信息的数据到指定的页面
function send($url,$post_data)
{
		$ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			
			curl_setopt($ch,CURLOPT_VERBOSE,1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$output = curl_exec($ch);
		    curl_close($ch);
			return $output;
}



?>
