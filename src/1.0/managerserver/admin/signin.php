<?php
/**
 * 管理员登录页面
 * author: 张程
*/

//载入初始化文件
require(dirname(__FILE__) . '/../includes/init.inc.php');

//如果是登出操作
if(isset($_GET['act']))
{	
	if($_GET['act']=='logout')
	{
		//销毁SESSION
		setcookie(session_name(), '', time()-3600);
		$_SESSION[]= array();
		session_destroy();
	}
}


//如果是回发页面
if(isset($_POST['submit']))
{
	$name = trim($_POST['username']);
	$pwd = trim($_POST['password']);
	if(!empty($name)&&!empty($pwd))
	{
		$sql = "select * from T_User where is_admin=1 and email='{$name}' and password=md5('{$pwd}')";
		$row = $db->FetchAssocOne($sql);
		if($db->NumRowsWithoutSql()>0)
		{
			//用户名
			setcookie('name', $row['email']);
			//用户ID
			setcookie('id', $row['user_id']);
			//管理员
			$_SESSION['admin'] = true;
			header('Location:admin.php');
		}
		else
		{
			$smarty->assign('name', $name);
			$smarty->assign('error', '密码错误！');
			$smarty->display('signin.html');
		}
	}
	else
		$smarty->display('signin.html');

}
else
{
	$smarty->display('signin.html');
}
?>