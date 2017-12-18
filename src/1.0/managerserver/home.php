<?php
/*
 * 空间主页面
 * author:张程
 */

//定义页面必须验证是否登录
define("AUTH", "TRUE");

//载入初始化文件
require(dirname(__FILE__) . "/includes/init.inc.php");

//载入文件处理助手
require(INC_PATH . "/file.helper.inc.php");

//当前页面ID(父目录的ID)
$pid = $zone_root;
if(!empty($_GET['id']))
{
	$pid = $_GET['id'];
}

$sql = "select * from T_FileInfo where file_id='{$pid}' and user_id='{$_COOKIE['id']}'";
$file = $db->FetchAssocOne($sql);
if($file)
{
	$dirpath = '';
	$dir_id = $file['parent_id'];
	while($dir_id != '0')
	{
		$sql = "select * from T_FileInfo where file_id='{$dir_id}' and user_id='{$_COOKIE['id']}'";
		$row = $db->FetchAssocOne($sql);

		//防止请求非文件夹
		if($row['file_type'] != 1)
			break;

		$dirpath = "<a href='home.php?id={$row['file_id']}'>{$row['file_name']}</a> » {$dirpath}";
		$dir_id = $row['parent_id'];
	}
	$dirpath .= "{$file['file_name']}";
	//赋值给目录路径
	$smarty->assign('dirpath', $dirpath);
}

$sql = "select * from T_FileInfo where parent_id='{$pid}' and user_id='{$_COOKIE['id']}' and is_del=0 order by file_type desc, file_name, version desc"; 

$list = $db->FetchAssoc($sql);

if($list)
{
	foreach($list as &$arr)
	{
		//如果是文件
		if($arr['file_type']==0)
		{
			$arr['file_url'] = ConvertFileNameToImg($arr['file_name']);
		}
		//如果是文件夹
		else
		{
			$arr['file_url'] = "tp-folder";
		}

		//处理时间，转换为yyyy-dd-mm H:i:s
		$arr['modify_time'] = date('Y-m-d H:i', $arr['modify_time']);

		//处理大小
		$arr['size'] = ComputeSize($arr['size']);

		//处理版本号
		if($arr['version'] > 1)
			$arr['version'] = "<span style='color:red;'>v{$arr['version']}</span>";
		else
			$arr['version'] = "v{$arr['version']}";
	}
}

//赋值给刷新页面id
$smarty->assign('id', $pid);
//赋值给创建文件夹父id
$smarty->assign('pid', $pid);
//赋值给文件列表
$smarty->assign('list', $list);
//赋值html title
$smarty->assign('title', 'ISTL | 我的云存储空间');
//赋值给session
$smarty->assign('sid', $_COOKIE['id']);

$smarty->display('home.html');
?>
