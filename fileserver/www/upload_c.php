<?php
require("conn.php");
$owner=$_POST['owner'];
$parent_id=$_POST['parent_id'];
$dirpath=$_POST['dirpath'];
$dir=$userPath.$_POST['owner'];

//������������ļ����ĺ���
function randStr($len=6) { 
$chars='0123456789'; // characters to build the password from 
mt_srand((double)microtime()*1000000*getmypid()); // seed the random number generater (must be done) 
$password=''; 
while(strlen($password)<$len) 
$password.=substr($chars,(mt_rand()%strlen($chars)),1); 
return $password; 
} 
//--------------------------------------------------------------------------------------------
//���ж�Ŀ¼�Ƿ���ڣ���������ڵĻ����Ƚ���Ŀ¼
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
$filename=$_FILES["upload"]["name"];//ȡ���ļ�����ʵ�ļ���
$pos=strrpos($_FILES["upload"]["name"],"."); //ȡ���ļ����к�׺���Ŀ�ʼλ��
$ext=substr($filename,$pos);//ȡ�ú�׺�����������
$url=$uploadfile;
      //  $filesize=$_FILES['Filedata']['size'];
		
		
if(1)
{

//����ʱ�ļ����µ����Ʊ��浽ָ����Ŀ¼
/*move_uploaded_file($f['tmp_name'],$dir.'/'.$newName_f);*/
		if(move_uploaded_file($_FILES['upload']['tmp_name'],$uploadfile))
		{
			//$owner=$_COOKIE["username"];
			$filesize=filesize("$uploadfile");
			$type=$ext;
			$createtime=date("Y-m-d H:i:s", filectime("$uploadfile"));
			$visittime=date("Y-m-d H:i:s", fileatime("$uploadfile"));
			$modifytime=date("Y-m-d H:i:s", filemtime("$uploadfile")); 
	//�����ļ��Ļ�����Ϣ�����Ȼ���͵�insertintodb_c.php������Ϣ���뵽���ݿ��С�
		
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
//�����ļ�������Ϣ�����ݵ�ָ����ҳ��
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
