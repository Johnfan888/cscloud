<?php
/*
 * 处理文件上传
 * author:张程
 */
 if(!empty($_FILES))
 {
	 $ip = $_POST['ip']; //元数据服务器ip
	 $pid = $_POST['pid'];//父文件夹
	 $uid = $_POST['c'];//用户ID
	 $size = $_FILES['the_files']['size'];//文件大小
	
	 //记录日志
	 //WriteLog("{$_FILES['the_files']['name']}请求上传");
	 WriteLog("uid:{$uid}请求上传");
	 //传送到元数据服务器的内容
	 $data = array('act' => 'safe', 'pid' => $pid, 'uid' => $uid, 'ip' => $_SERVER['SERVER_ADDR'], 'name' =>$_FILES['the_files']['name'], 'size' => $size);
	 //访问manager server上检查上传是否合法
	 //release时配置
	 $url = "http://{$ip}/upload_curl.php" ;
	 //debug时配置
	 //$url = "http://localhost/istl/managerserver/upload_curl.php" ;
	 $ch = curl_init();
	 $curl_opts[CURLOPT_URL] = $url;
	 $curl_opts[CURLOPT_HEADER] = false;
	 $curl_opts[CURLOPT_RETURNTRANSFER] = true;
	 $curl_opts[CURLOPT_POST] = true;
	 $curl_opts[CURLOPT_POSTFIELDS] = $data;
	 $curl_opts[CURLOPT_TIMEOUT] = 0;
	 curl_setopt_array($ch, $curl_opts); //设置选项
	
	//记录日志
	 WriteLog("请求服务器{$ip}，检查用户ID为:{$uid}，请求的父文件夹{$pid}是否合法");

	//执行结果，为json格式
	$result = curl_exec($ch);		 
	//解析json字符串
	$json = json_decode($result, true);
	curl_close($ch);

	//记录日志
	WriteLog($result);

	if($json['result'])
	 {
		//合法操作
		$file_id = UniqueName();//文件名字
		$uid = $json['uid'];//获取到的用户ID
		$path = rtrim($json['path'], '/');//获取到的路径
		
	 	if(!file_exists("{$path}/{$uid}"))
		{
			//记录日志
			WriteLog("创建目录{$path}/{$uid}");
			mkdir("{$path}/{$uid}");
			WriteLog('目录创建完毕');
		}
		
		//路径的分割
		$arr=str_split($file_id,8);
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
			}
			if(!file_exists("{$path}/{$uid}/{$arr0}/{$arr1}")){
				mkdir("{$path}/{$uid}/{$arr0}/{$arr1}");
			}
			if(!file_exists("{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}")){
				mkdir("{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}");
			}
			if(!file_exists("{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}/{$arr3}")){
				mkdir("{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}/{$arr3}");
			}
		
		
		//echo 'hello';
		//$targetPath = "{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/";//创建的路径
		  $targetPath = "{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/$file_id";
		//备份服务器信息   ???
		$ha_ip = $json['haip'];
		$ha_path = $json['hapath'];

		

		if(move_uploaded_file($_FILES['the_files']['tmp_name'], $targetPath))
		{
			//记录日志
			WriteLog("上传成功");

			$version = 1;
			//如果是高版本
			if(!empty($json['base']))
			{
				WriteLog("正在进行文件多版本比对");
				$version = $json['version'];
				//文件差量比对
				
				//第一版本的文件id
				$file=$json['base'];
				$arr=str_split($file,8);
				$arr0=substr($arr[0], -1);
				$arr1=substr($arr[1], -1);
				$arr2=substr($arr[2], -1);
				$arr3=substr($arr[3], -1);
				$arr0=md5($arr0);
				$arr1=md5($arr1);
				$arr2=md5($arr2);
				$arr3=md5($arr3);
				//$base = "{$path}/{$uid}/{$json['base']}";
				 $base = "{$path}/{$uid}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/$file";
				//$base = "{$path}/{$uid}/{$json['base']}";
				
				//产生差量
				//system("/usr/bin/sudo /usr/bin/diff -auN {$base} {$targetPath} > {$targetPath}.patch");
				system("/usr/bin/diff -auN {$base} {$targetPath} > {$targetPath}.patch");

				//删除上传的文件
				unlink($targetPath);
				
				//重命名patch文件
				rename("{$targetPath}.patch", $targetPath);
				
				//记录日志
				WriteLog("多版本处理结束");
			}

			 //重新计算用户空间已用量
			/* if ($handle = opendir("{$path}/{$uid}")) 
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
			 }*/

			 //执行插入数据库操作
			 $data = array('act' => 'sql', 'pid' => $pid, 'uid' => $uid, 'id' => $file_id, 'name' =>$_FILES['the_files']['name'], 'size' => $size,  'version' => $version);

			 //执行managerserver插入数据库操作
			 //release时配置
			 $url = "http://{$ip}/upload_curl.php" ;
			 //debug时配置
			 //$url = "http://localhost/istl/managerserver/upload_curl.php" ;
			 $ch = curl_init();
			 $curl_opts[CURLOPT_URL] = $url;
			 $curl_opts[CURLOPT_HEADER] = false;
			 $curl_opts[CURLOPT_RETURNTRANSFER] = true;
			 $curl_opts[CURLOPT_POST] = true;
			 $curl_opts[CURLOPT_POSTFIELDS] = $data;
			 $curl_opts[CURLOPT_TIMEOUT] = 0;
			 curl_setopt_array($ch, $curl_opts);
			
			//记录日志
			 WriteLog("正在请求执行插入数据库操作");

			 //执行结果，为json格式
			 $result = curl_exec($ch);		 
			 //解析json字符串
			 $json = json_decode($result, true);
			 curl_close($ch);

			 if($json['result'])
			 {
				 WriteLog("上传成功");
				echo '{result:true, msg:"上传成功！"}';
			 }
			 else
			 {
				 WriteLog("上传失败，原因{$result}{$json}");
				 echo '{result:false, msg:"上传失败！"}';
			 }
		}
		else
		{
			WriteLog('上传失败');
		}
	 }
	 else
	 {
		 echo "{result:false, msg:'{$json['msg']}'}";
	 }
 }




 //生成唯一的文件名
function UniqueName()
{ 
	$token = md5(uniqid(rand()));
	return $token;
}

//记录日志
function WriteLog($msg)
{
	 $basepath =  '/var/log/csc';
	 if(!file_exists($basepath))
		mkdir($basepath);

	 $logpath = $basepath . '/upload/';
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
