<?php 
	function send($url,$post_data) //Curl调用
	{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	//curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$result=curl_exec($ch);
	curl_close($ch);
	return $result;
	}
	$method = $_GET['method'];
	$owner = $_GET['owner']; //use useid replace owner
	$dirpath = $_GET['dirpath']; //存储路径
	$filename = $_GET['filename']; //文件名
//TODO muti-version info
	$version=$_GET['version']; //版本
	//FDDM 
	   $dirpath=$dirpath.$owner."/";
	   $arr1=substr($filename,0,1);
	   $arr1=md5($arr1);
	   $arr2=substr($filename,1,1);
	   $arr2=md5($arr2);
	   //$dirpath=$dirpath.$arr1."/".$arr2."/";
	   $target1=$dirpath.$arr1."/";
	   $target2=$target1.$arr2."/";
	   $fullPath=$target2.$filename;

	//删除文件 @抑制报错信息
	@$flag = unlink($fullPath);
    
	//delete empty dir
	if($flag)
	{
		 if(dir_is_empty($target2)){
		 	rmdir($target2);
		 }
		if(dir_is_empty($target1)){
		 	rmdir($target1);
		 }
		echo "true";
	}
	else
	{
		echo 'false'; 
	}

$url="http://".$_GET['managerserverip']."/manage/csc_http_delete_deletefromdb.php";
$post_data=array(
	'filename'=>$filename,
    'userid'=>$owner
);
send($url,$post_data);
function dir_is_empty($dir){ 
	if($handle = opendir($dir)){  
		while($item = readdir($handle)){   
			if ($item != "." && $item != ".."){
				return false;  } 
			/*else{
				return true;}*/
		} 
	}
	return true;
}
?>