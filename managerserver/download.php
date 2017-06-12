<?php
/*
 * 处理文件下载页面
 * author:张程
 */

//定义页面必须验证是否登录
define("AUTH", "TRUE");

//载入初始化文件
require(dirname(__FILE__) . "/includes/init.inc.php");

//载入日志记录助手
require(INC_PATH . '/log.helper.inc.php');

if(!empty($_GET['id']))
{
	//判断要下载的文件的ID是否是属于登录人的
	$id = $_GET['id'];
	$sql = "select * from T_FileInfo where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
	$file = $db->FetchAssocOne($sql);

	//说明请求文件是登陆人的
	if($db->NumRowsWithoutSql() > 0)
	{
		$sql = "select * from T_FileLocation where file_id='{$id}'";
		$fileinfo = $db->FetchAssocOne($sql);
		
		//服务器存在该文件
		if($fileinfo)
		{
			//判断用户文件主文件所在的服务器是否正常
			$sql = "select status from T_Server where server_ip='{$fileinfo['server_ip']}'";
			$status = $db->FetchAssocOne($sql);

			//主文件服务器正常，直接下载
			if($status['status']==1)
			{
				$ip = $fileinfo['server_ip'];
				$path = $fileinfo['file_path'];
			}
			//主文件服务器失效，去副本服务器下载
			else
			{
				$ip = $fileinfo['ha_server_ip'];
				$path = $fileinfo['ha_file_path'];
			}

			//判断最近操作Cache表是否有记录
			$sql = "select count(1) as num from T_Cache where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
			$num = $db->FetchAssocOne($sql);
			$time = time();
			//说明有该条访问记录，修改
			if($num['num'] > 0)
			{
				$sql = "update T_Cache set modify_time='{$time}' where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
				$db->Query($sql);
			}
			//没有该条访问记录，插入
			else
			{
				$sql = "insert into T_Cache select * from T_FileInfo where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
				$db->Query($sql);
				
				//更新访问时间
				$sql = "update T_Cache set modify_time='{$time}' where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
				$db->Query($sql);
			}

			 $url = "http://{$ip}/download.php" ;
			 $name = rawurlencode($file['file_name']);

			//查看请求的文件的版本，如果是v1直接下载，如果高于v1，则需要将文件先恢复，然后在下载
			if($file['version'] > 1)
			{
				$sql = "select * from T_FileInfo where file_name='{$file['file_name']}' and parent_id='{$file['parent_id']}' and user_id='{$_COOKIE['id']}' and version='1'";
				$base = $db->FetchAssocOne($sql);
				$bid = $base['file_id'];
				header("Location:{$url}?path={$path}&user={$_COOKIE['id']}&id={$file['file_id']}&name={$name}&base={$bid}");
			}
			else
			{
				 header("Location:{$url}?path={$path}&user={$_COOKIE['id']}&id={$file['file_id']}&name={$name}");
			}
		}
		//服务器不存在该文件，发生数据冗余
		else
		{
			WriteLog('download', "{$_COOKIE['name']}/{$id}", '请求下载文件错误，可能发生数据冗余！');
			exit('File Error！');
		}
	}
	//非法请求或错误请求
	else
	{
		exit('Bad Request!');
	}
}

?>