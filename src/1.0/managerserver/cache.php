<?php
/*
 * 最近操作列表页面
 * author:张程
 */


//定义页面必须验证是否登录
define("AUTH", "TRUE");

//载入初始化文件
require(dirname(__FILE__) . "/includes/init.inc.php");

//载入文件处理助手
require(INC_PATH . "/file.helper.inc.php");

//删除90天以上的记录
$time = time();
$deadline = $time-3600*24*90;
$sql = "delete from T_Cache where modify_time<'{$deadline}' and user_id='{$_COOKIE['id']}'";
$db->Query($sql);

//获取查询天数
if(isset($_GET['day']))
{
	$day = intval($_GET['day']);
	if($day==1||$day==3||$day==7||$day==30||$day==90)
	{
		$endtime = $time-3600*24*$day;
		$sql="select * from T_Cache where user_id='{$_COOKIE['id']}' and modify_time<='{$time}' and modify_time>='{$endtime}' order by modify_time desc limit 0, 20";
	}
	else
	{
		$sql="select * from T_Cache where user_id='{$_COOKIE['id']}' order by modify_time desc limit 0, 20";
	}
}
else
{
	$sql="select * from T_Cache where user_id='{$_COOKIE['id']}' order by modify_time desc limit 0, 20";
}

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
		$arr['modify_time'] = date('Y-m-d', $arr['modify_time']);

		//处理大小
		$arr['size'] = ComputeSize($arr['size']);

		//处理版本号
		if($arr['version'] > 1)
			$arr['version'] = "<span style='color:red;'>v{$arr['version']}</span>";
		else
			$arr['version'] = "v{$arr['version']}";

	}
}

$smarty->assign('list', $list);
$smarty->display('cache.html');

?>