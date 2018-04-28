<?php
//不允许直接请求该页面
if(!defined('INC')) exit('Bad Request');

/**
 * 日志帮助方法
 * author:张程
 */


//记录日志
function WriteLog($logDirName, $msg)
{
	 $basepath =  '/var/log/csc';
	 if(!file_exists($basepath))
		mkdir($basepath);

	 $logpath = $basepath . "/{$logDirName}/";
	 if(!file_exists($logpath))
		 mkdir($logpath);

	 $fp = fopen($logpath . date('Y-m-d') . '.txt', 'ab');
	 fwrite($fp, 'time:' . date('Y-m-d H:i:s'));
	 fwrite($fp, "\n");
	 fwrite($fp, "<message>  {$msg}");
	 fwrite($fp, "\n");
	 fwrite($fp, "\n");
	 fclose($fp);
}
?>
