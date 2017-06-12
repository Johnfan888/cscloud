<?php
/*
 * 用户管理页面
 * author:张程
 */

//载入初始化文件
require(dirname(__FILE__) . "/../includes/init.inc.php");

//定义页面必须验证是否登录
define("AUTH", "TRUE");

//验证是否为管理员
if($_SESSION['admin'] != true)
{
	exit;
}

if(isset($_GET['act']) && !empty($_GET['id']))
{
	/*删除操作，取消
	if($_GET['act'] == 'del')
	{
		$id = $_GET['id'];
		if($id != $_SESSION['id'])
		{
			//删除用户信息
			$sql = "delete from T_User where user_id='{$id}'";
			if($db->Query($sql) > 0)
			{
				$sql = "delete from T_UserZone where user_id='{$id}'";
				if($db->Query($sql) > 0)
					$text = '<script>alert("删除成功！");</script>';
				else
					$text = '<script>alert("删除用户成功，删除用户空间信息失败！");</script>';
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
	//编辑操作
	if($_GET['act'] == 'edit')
	{
		$id = $_GET['id'];
		$sql = "select * from T_User where user_id='{$id}'";
		$user = $db->FetchAssocOne($sql);
		$smarty->assign('edit', 'true');
		$smarty->assign('id', $user['user_id']);
		$smarty->assign('email', $user['email']);
		$smarty->assign('admin', array(
			1 => '是',
			0 => '否'));
		$smarty->assign('checked', array(
			1 => '正常',
			0 => '关闭'));
		$smarty->assign('adminflag', $user['is_admin']);
		$smarty->assign('checkedflag', $user['is_checked']);
	}
}

//更新操作
else if(!empty($_POST['submit']))
{
	$id = $_POST['id'];
	$admin = $_POST['admin'];
	$checked = $_POST['checked'];
	$sql = "update T_User set is_admin='{$admin}', is_checked='{$checked}' where user_id='{$id}'";
	if($db->Query($sql) > 0)
			$text = '<script>alert("修改成功！");</script>';
	else
			$text = '<script>alert("修改失败！");</script>';
	$smarty->assign('text', $text);
}

$sql = "select * from T_User order by email";
$list = $db->FetchAssoc($sql);

if($list)
{
	foreach ($list as &$row)
	{
		if($row['is_admin'] == 1)
			$row['is_admin'] = '是';
		else
			$row['is_admin'] = '否';
		if($row['is_checked'] == 1)
			$row['is_checked'] = '正常';
		else
			$row['is_checked'] = '关闭';
	}
	$smarty->assign('list', $list);
}
$smarty->display('admin.html');

?>