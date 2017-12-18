<?php
/*
 * 空间信息管理页面
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
	//编辑操作
	if($act == 'edit')
	{
		//如果是提交页面
		if(!empty($_POST['submit']))
		{
			$id = $_POST['id'];
			$size = $_POST['size']; 
			$sql = "update T_UserZone set useable_size='{$size}' where user_id='{$id}'";

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
		$sql = "select z.*, u.email  from T_UserZone as z, T_User as u where z.user_id=u.user_id and z.user_id='{$id}'";
		$zone = $db->FetchAssocOne($sql);
		if($zone)
		{
			$smarty->assign('id', $zone['user_id']);
			$smarty->assign('email', $zone['email']);
			$smarty->assign('ip', $zone['server_ip']);
			$smarty->assign('ha_ip', $zone['ha_server_ip']);
			$smarty->assign('useable', $zone['useable_size']);
			$smarty->assign('used', $zone['used_size']);
		}
	}
	/*删除操作，取消
	else if($act  == 'del' && !empty($_GET['id']))
	{
		$id = $_GET['id'];
		if($id != $_SESSION['id'])
		{
			//删除用户信息
			$sql = "delete from T_UserZone where user_id='{$id}'";
			if($db->Query($sql) > 0)
			{
				$sql = "delete from T_User where user_id='{$id}'";
				if($db->Query($sql) > 0)
					$text = '<script>alert("删除成功！");</script>';
				else
					$text = '<script>alert("删除空间信息成功，删除用户失败！");</script>';
			}
			else
			{
				$text = '<script>alert("删除失败！");</script>';
			}
		}
		else
		{
			$text = '<script>alert("抱歉，您不能删除自己的账号！");</script>';
		}
		$smarty->assign('text', $text);
	}
	*/
	$smarty->assign('act', $act);
}

//载入文件处理助手
require(INC_PATH . '/file.helper.inc.php');

$sql = "select z.*, u.email  from T_UserZone as z, T_User as u where z.user_id=u.user_id order by u.email";
$zone = $db->FetchAssoc($sql);

if($zone)
{
	foreach ($zone as &$row)
	{
		$row['useable_size'] = ComputeSize($row['useable_size']);
		$row['used_size'] = ComputeSize($row['used_size']);
	}
	$smarty->assign('list', $zone);
}
$smarty->display('zone.html');

?>