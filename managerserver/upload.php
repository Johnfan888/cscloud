<?php
/*
 * 处理上传文件
 * author:张程
 */

//定义页面必须验证是否登录
define("AUTH", "TRUE");

//载入初始化文件
require(dirname(__FILE__) . '/includes/init.inc.php');

//载入用户信息处理助手
require(INC_PATH . '/user.helper.inc.php');

//载入文件处理助手
require(INC_PATH . '/file.helper.inc.php');

//载入日志记录助手
require(INC_PATH . '/log.helper.inc.php');

if(!empty($_GET['pid']))
{
	//父目录ID
	$pid = $_GET['pid'];
	//判断请求的父ID是否是文件夹且属于登录人
	$sql = "select * from T_FileInfo where file_id='{$pid}' and file_type=1 and user_id='{$_COOKIE['id']}'";
	$parent = $db->FetchAssocOne($sql);
	if($db->NumRowsWithoutSql() > 0)
	{
		//查询用户存储文件的服务器IP
		$ip = UserFileIP($_COOKIE['id']);
		if(empty($ip))
		{
			//说明未分配，负载均衡策略为用户分配一个
			require(INC_PATH . '/loadbalance.inc.php');
			$servers = SelectFileServer();
			$ip = $servers['ip'];
			UpdateFileIP($_COOKIE['id'], $ip);
			UpdateHAFileIP($_COOKIE['id'], $servers['ha_ip']);
		}
		echo "{result:true, to:'{$ip}', from:'{$_SERVER['SERVER_ADDR']}'}";
	}
	else
	{
		WriteLog('upload', $_COOKIE['id'], '请求错误！');
		echo '{result:false, msg:"错误的请求！"}';
	}
}
?>
