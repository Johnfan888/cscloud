<?php
/*
 * 重命名文件页面
 * author:张程
 */

//定义页面必须验证是否登录
define("AUTH", "TRUE");

//载入初始化文件
require(dirname(__FILE__) . "/includes/init.inc.php");

if(!empty($_GET['id']))
{
	//判断要重命名的文件的ID是否是属于登录人的
	$id = $_GET['id'];
	$sql = "select * from T_FileInfo where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
	$file = $db->FetchAssocOne($sql);
	//说明请求文件是登陆人的
	if($db->NumRowsWithoutSql() > 0)
	{
		$name = $_GET['name'];
		$extension = pathinfo($file['file_name']) ;
		$name = empty($extension['extension']) ? $name : "{$name}.{$extension['extension']}";

		//分析同一级目录下是否有重复的文件名或文件夹名
		$sql = "select count(1) as num from T_FileInfo where file_name='{$name}' and file_type='{$file['file_type']}' and parent_id ='{$file['parent_id']}' and user_id='{$_COOKIE['id']}'";
		$num = $db->FetchAssocOne($sql);
		if($num['num'] > 0)
		{
			//有重名
			if($file['file_type'] == 1)
				echo '{result:false, msg:"抱歉，该目录名称已存在！"}';
			else
				echo '{result:false, msg:"抱歉，该文件夹下已存在相同的文件名！"}';
			exit();
		}
		else
		{
			//查询要修改的文件的名字
			$sql = "select * from T_FileInfo where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
			$tmp = $db->FetchAssocOne($sql);

			//修改数据库中的全部信息
			$sql = "update T_FileInfo set file_name ='{$name}' where file_name='{$tmp['file_name']}' and parent_id ='{$file['parent_id']}' and user_id='{$_COOKIE['id']}'";
			$db->Query($sql);

			//修改缓存Cache表
			$time = time();
			$sql = "update T_Cache set modify_time ='{$time}', file_name='{$name}' where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
			$db->Query($sql);

			echo '{result:true, msg:"修改成功！"}';
		}
	}
	else
	{
		echo '{result:false, msg:"错误的请求！"}';
	}
}
else
{
	//文件名不能为空
	echo '{result:false, msg:"重命名名称不能为空!"}';
	exit();
}
?>