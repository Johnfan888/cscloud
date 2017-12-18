<?php
/*
 * 服务器管理页面
 * author:张程
 */

//载入初始化文件
require(dirname(__FILE__) . "/../includes/init.inc.php");

//定义页面必须验证是否登录
define("AUTH", "TRUE");

//验证是否为管理员
if(empty($_SESSION['admin']))
{
	exit;
}

if(isset($_GET['act']))
{
	$act = trim($_GET['act']);
	//新增服务器操作
	if($act == 'add')
	{
		//如果是提交页面
		if(!empty($_POST['submit']))
		{
			$ip = substr($_POST['ip'], 0, 15);
			$path = $_POST['path'];
			$sql = "insert into T_Server values (NULL, '{$ip}', '{$path}', '1')";
			if($db->Query($sql) > 0)
					$text = '<script>alert("添加成功！");</script>';
			else
					$text = '<script>alert("添加失败！");</script>';
			$smarty->assign('text', $text);
			$smarty->assign('ip', $ip);
		}
		//载入文件服务器配置config
		$file = ROOT_PATH . '/admin/config/server.txt';
		$config = @fopen($file, 'r');
		if($config)
		{
			if(!feof($config))
			{
				$defaultpath = fgets($config);
				$defaultpath = trim(strstr($defaultpath, '/'));
				$smarty->assign('path', $defaultpath);
			}
			fclose($config);
		}
	}
	/*修改服务器，取消
	else if($act == 'edit')
	{
		//如果是提交页面
		if(!empty($_POST['submit']))
		{
			$id = $_POST['id'];
			$ip = $_POST['ip'];
			$path = $_POST['path'];
			$sql = "update T_Server set server_ip='{$ip}', file_path='{$path}' where server_id='{$id}'";
			
			if($db->Query($sql) > 0)
				$text = '<script>alert("修改成功！");</script>';
			else
				$text = '<script>alert("修改失败！");</script>';
			$smarty->assign('text', $text);
		}
		else
		{
			$id = $_GET['id'];
		}
		$sql = "select * from T_Server where server_id='{$id}'";
		$server = $db->FetchAssocOne($sql);
		if($server)
		{
			if($server['status'] == 1)
				$server['status'] = '正常';
			else
				$server['status'] = '故障';
			$smarty->assign('id', $id);
			$smarty->assign('ip', $server['server_ip']);
			$smarty->assign('path', $server['file_path']);
			$smarty->assign('status', $server['status']);
		}
	}
	//删除服务器，取消
	else if($act == 'del')
	{
		$id = $_GET['id'];
		$sql = "delete from T_Server where server_id='{$id}'";
		if($db->Query($sql) > 0)
			$text = '<script>alert("删除成功！");</script>';
		else
			$text = '<script>alert("删除失败！");</script>';
		$smarty->assign('text', $text);
	}
	*/
	$smarty->assign('act', $act);
}


$sql = "select * from T_Server order by server_ip";
$servers = $db->FetchAssoc($sql);

if($servers)
{
	foreach ($servers as &$row)
	{
		if($row['status'] == 1)
			$row['status'] = '正常';
		else
			$row['status'] = '故障';
	}
	$smarty->assign('list', $servers);
}
$smarty->display('server.html');

?>