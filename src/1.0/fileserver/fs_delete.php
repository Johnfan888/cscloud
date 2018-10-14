<?php
/*
 * 删除服务器文件
 * author:张程
 */

if(isset($_POST['path']))
{
	$path = $_POST['path'];
	$uid = $_POST['uid'];
	$id = $_POST['id'];
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
    	//删除空目录的过程
		$target1="{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}/{$arr3}";
		$target2="{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}";
		$target3="{$path}/{$uid}/{$arr0}/{$arr1}";
		$target4="{$path}/{$uid}/{$arr0}";
		
	//$target =  "{$path}/{$uid}/{$id}";

	//删除文件 @抑制报错信息
	@$flag = unlink($target);

	if($flag)
	{
		WriteLog("删除文件{$path}成功，重新计算用户空间大小");
		 //重新计算用户空间已用量
		 //删除空目录的过程
		 if(dir_is_empty($target1)){
		 	rmdir($target1);
		 }
		if(dir_is_empty($target2)){
		 	rmdir($target2);
		 }
		if(dir_is_empty($target3)){
		 	rmdir($target3);
		 }
		if(dir_is_empty($target4)){
		 	rmdir($target4);
		 }
		 if ($handle = opendir("{$path}/{$uid}")) 
		 {
			 //记录日志
			 WriteLog("重新计算用户空间大小");

			$used = 0;
			//遍历目录
			while (false !== ($file = readdir($handle)))
			{
				if ($file != "." && $file != "..") {
					$used +=  sprintf("%u", filesize("{$path}/{$uid}/{$file}"));
				}
			}

			WriteLog("大小为{$used}");
			closedir($handle);
		 }
		echo '{"result":true, "msg":"删除成功！", "used":"' . $used . '"}';
	}
	else
	{
		WriteLog("删除文件{$path}失败！");
		echo '{"result":false, "msg":"删除文件失败！"}'; 
	}
}

function WriteLog($msg)
{
	 $basepath =  '/var/log/csc';
	 if(!file_exists($basepath))
		mkdir($basepath);

	 $logpath = $basepath . '/delete/';
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