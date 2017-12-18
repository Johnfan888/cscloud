<?php
/*
 * 备份文件页面
 * author:张程
 */

 if(!empty($_FILES))
 {
	 //ms的IP以及key
	 $ms_ip = $_POST['ip'];
	 $key = $_POST['key'];

	 //验证是否合法
	 $ch = curl_init();
	 $url = "http://{$ms_ip}/key.php" ;
	 $data = array('key' => $key);

	 $curl_opts[CURLOPT_URL] = $url;
	 $curl_opts[CURLOPT_HEADER] = false;
	 $curl_opts[CURLOPT_RETURNTRANSFER] = true;
	 $curl_opts[CURLOPT_POST] = true;
	 $curl_opts[CURLOPT_POSTFIELDS] = $data;
	 $curl_opts[CURLOPT_TIMEOUT] = 0;
	 curl_setopt_array($ch, $curl_opts);

	 $result = curl_exec($ch);
	 $json = json_decode($result, true);
	 if(!$json['result'])
	 {
		 WriteLog('非法备份上传！');
		 exit;
	 }

	 $uid = $_POST['uid']; //用户ID
	 $path = $_POST['path']; //基础路径
	 $id = $_POST['id']; //文件Id
	
	 $target = "{$path}/{$uid}"; //用户ID下
	 if(!file_exists($target))
	 {
		 mkdir($target);
	 }
	// $target = "{$target}/{$id}";
	 //路径的分割
		$arr=str_split($id,8);
		$arr0=substr($arr[0], -1);
		$arr1=substr($arr[1], -1);
		$arr2=substr($arr[2], -1);
		$arr3=substr($arr[3], -1);
		$arr0=md5($arr0);
		$arr1=md5($arr1);
		$arr2=md5($arr2);
		$arr3=md5($arr3);
		if(!file_exists("{$path}/{$uid}/{$arr0}")){
			mkdir("{$path}/{$uid}/{$arr0}");
			if(!file_exists("{$path}/{$uid}/{$arr0}/{$arr1}")){
				mkdir("{$path}/{$uid}/{$arr0}/{$arr1}");
			}
			if(!file_exists("{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}")){
				mkdir("{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}");
			}
			if(!file_exists("{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}/{$arr3}")){
				mkdir("{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}/{$arr3}");
			}
		
		}
		//echo 'hello';
		//$targetPath = "{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/";//创建的路径
		  $target = "{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/{$id}";

	 WriteLog('开始备份文件！');
	 if(move_uploaded_file($_FILES['file']['tmp_name'], $target))
	 {
		 //备份成功
		 WriteLog("backup file : {$target} successfully !");
		 $result = array('result' => true, 'msg' => '备份成功！');
		 echo json_encode($result);
	 }
	 else
	 {
		 //备份失败
		 WriteLog("backup file : {$target} failed !");
		 $result = array('result' => false, 'msg' => '备份失败！');
		 echo json_encode($result);
	 }
 }

//记录日志
function WriteLog($msg)
{
	 $basepath =  '/var/log/csc';
	 if(!file_exists($basepath))
		mkdir($basepath);

	 $logpath = $basepath . '/backup/';
	 if(!file_exists($logpath))
		 mkdir($logpath);

	 $fp = fopen($logpath . date('Y-m-d') . '.txt', 'ab');
	 fwrite($fp, 'time:' . date('Y-m-d H:i:s'));
	 fwrite($fp, "\n");
	 fwrite($fp, "message : {$msg}");
	 fwrite($fp, "\n");
	 fwrite($fp, "\n");
	 fclose($fp);
}
 ?>