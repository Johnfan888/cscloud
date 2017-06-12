<?php
/*
 * 新建服务器文件夹
 * author:张程
 *该页面暂时不用
 */

exit;

if(isset($_POST['path']))
{
	$file = $_POST['path'];
    $flag = mkdir($file, 0777);
	if($flag)
	{
		echo '{"result":true, "msg":"新建成功！"}';
	}
	else
	{
		//新建失败，记录日志
		WriteErrorLog($file, '新建文件夹失败！' )
		echo '{"result":false, "msg":"新建文件夹失败！"}'; 
	}
}

function WriteErrorLog($filename, $msg)
{
	$path = '/var/log/errorlogs/newdir/' . date('Y-m-d') . '.txt';
	$log = fopen($path, 'ab');
	fwrite($log, '******************************************\n');
	fwrite($log, 'time:' . date('Y-m-d H:i:s') . '\n');
	fwrite($log, "create new directory {$filename} error \n");
	fwrite($log, "error message : {$msg} \n");
	fwrite($log, '******************************************\n');
	fwrite($log, '\n');
	fclose($log);
}
?>