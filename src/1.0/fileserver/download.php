<?php
/*
 * 处理文件下载
 * author:张程
 */

if(isset($_GET['path']))
{
	$path = $_GET['path'];
	$user = $_GET['user'];
	$id = $_GET['id'];
	$path = rtrim($path, '/');
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

	//$targetPath = "{$path}/{$user}/{$id}";
    $targetPath = "{$path}/{$user}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/{$id}";
	$name = !empty($_GET['name']) ? rawurldecode($_GET['name']) : 'downloaded';

	 if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) 
		 $name = rawurlencode($name);

	//判断一下如果是第一版本的文件则直接下载，如果是其他版本的文件，则要在下载完成后，将新版本文件还原成旧版本
	clearstatcache();
	if(isset($_GET['base']))
	{
		$base = $_GET['base'];
		$arr=str_split($base,8);
		$arr0=substr($arr[0], -1);
		$arr1=substr($arr[1], -1);
		$arr2=substr($arr[2], -1);
		$arr3=substr($arr[3], -1);
		$arr0=md5($arr0);
		$arr1=md5($arr1);
		$arr2=md5($arr2);
		$arr3=md5($arr3);

	//$targetPath = "{$path}/{$user}/{$id}";
    	$base = "{$path}/{$user}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/{$base}";
		//$base = "{$path}/{$user}/{$base}";
		$temp = "{$path}/{$user}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/cvfile";
		
		$cmd = "/usr/bin/patch -o {$temp} {$base} {$targetPath}";
		exec($cmd, $ls);
		//判断该高版本文件的patch补丁的文件大小是否为0
		if(filesize($targetPath) > 0)
		{
			$cmd = "/usr/bin/patch -o {$temp} {$base} {$targetPath}";
			exec($cmd, $ls);

			//再下载文件
			download($temp, $name);

			//再删除临时文件
			@unlink($temp);
		}
		else
		{
			//如果patch文件大小为0，直接给其base文件的下载地址，这时候patch命令不起作用
			//下载文件
			download($base, $name);
		}
	}
	else
	{
	    //说明是第一版本的文件，直接下载
		download($targetPath, $name);
	}	
}

//下载文件的函数,其中的注释都是调试中要用到的
function download($file, $filename)
{
	if(!file_exists($file))  
    { 
		 header("Content-type:text/html; Charset=utf-8");
        echo '对不起,你要下载的文件不存在。';  
    }
	else
	{
		header('Expires: 0');
		header('Content-type: application/octet-stream');
		header("Content-Disposition: attachment; filename={$filename}");
		header('Content-Transfer-Encoding: binary');
		@readfile($file);
	}
}
?>