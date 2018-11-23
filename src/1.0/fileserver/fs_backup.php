<?php
/*
 * 备份文件页面
 * author:张程
 */

if(!empty($_POST['uid']))
{
	 $uid = $_POST['uid'];
	 $path = $_POST['path'];//文件服务器基础路径
	 $id = $_POST['id'];
	 $ha_ip = $_POST['ha_ip']; //副本ip
	 $ha_path = $_POST['ha_path'];//副本路径
	 $ms_ip = $_POST['ip'];
	 $key = $_POST['key'];
	 //分解目录
	    $arr=str_split($id,8);
		$arr0=substr($arr[0], -1);
		$arr1=substr($arr[1], -1);
		$arr2=substr($arr[2], -1);
		$arr3=substr($arr[3], -1);
		$arr0=md5($arr0);
		$arr1=md5($arr1);
		$arr2=md5($arr2);
		$arr3=md5($arr3);
		$target = "{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/{$id}";
	 //$target = "{$path}/{$uid}/{$id}";

	 $ch = curl_init();
	 $url = "http://{$ha_ip}/backup_curl.php" ;
	 $data = array('uid' => $uid, 'path' => $path, 'id' => $id, 'ip' => $ms_ip, 'key' => $key, 'file' => "@{$target}");

	 $curl_opts[CURLOPT_URL] = $url;
	 $curl_opts[CURLOPT_HEADER] = false;
	 $curl_opts[CURLOPT_RETURNTRANSFER] = true;
	 $curl_opts[CURLOPT_POST] = true;
	 $curl_opts[CURLOPT_POSTFIELDS] = $data;
	 $curl_opts[CURLOPT_TIMEOUT] = 0;
	 curl_setopt_array($ch, $curl_opts);

	 $result = curl_exec($ch);

	 echo $result;
}
?>