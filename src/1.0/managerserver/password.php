<?php
/*
 * 修改密码
 * author:张程
 */

//定义页面必须验证是否登录
define("AUTH", "TRUE");

//载入初始化文件
require(dirname(__FILE__) . '/includes/init.inc.php');
if(isset($_POST['submit']))
{
	if(!empty($_POST['passwd0']) && !empty($_POST['passwd1']) && !empty($_POST['passwd2']))
	{
		//修改密码
		$pwd0 = $_POST['passwd0'];
		$pwd1 = $_POST['passwd1'];
		$pwd2 = $_POST['passwd2'];

		if($pwd1 != $pwd2)
		{
			//两次密码不一样
			$smarty->assign('msg', '两次输入的密码不一样！');
		}
		else
		{
			//验证旧密码是否正确
			$sql = "select count(1) as num from T_User where user_id='{$_COOKIE['id']}' and password=md5('{$pwd0}') and is_checked=1";
			$num = $db->FetchAssocOne($sql);
			if($num['num'] > 0)
			{
				//可以修改
				$sql = "update T_User set password=md5('{$pwd1}') where user_id='{$_COOKIE['id']}'";
				if($db->Query($sql) >0)
					$smarty->assign('msg', '<script>alert("修改成功！"); window.location.href="login.php?act=logout";</script>！');
				else
					$smarty->assign('msg', '修改失败！');
			}
			else
			{
				//旧密码错误
				$smarty->assign('msg', '密码错误！');
			}
		}
	}
}
$smarty->display('password.html');
?>