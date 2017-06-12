<?php
/**
 * 登录页面
 * author: 张程
*/

//载入初始化文件
require(dirname(__FILE__) . '/includes/init.inc.php');

//如果是登出操作
if(isset($_GET['act']))
{	
	if($_GET['act']=='logout')
	{
		//销毁SESSION
		setcookie(session_name(), '', time()-3600);
		$_SESSION[]= array();
		session_destroy();

		//销毁cookie
		setcookie('id', '', time()-3600);
		setcookie('name', '', time()-3600);
	}
}


//如果是回发页面
if(isset($_POST['submit']))
{
	$name = trim($_POST['username']);
	$pwd = trim($_POST['password']);
	if(!empty($name)&&!empty($pwd))
	{
		$sql = "select * from T_User where email='{$name}' and password=md5('{$pwd}') and is_checked=1";
		$row = $db->FetchAssocOne($sql);
		if($db->NumRowsWithoutSql()>0)
		{
			//用户名
			setcookie('name', $row['email']);
			//用户ID
			setcookie('id', $row['user_id']);
			header('Location:cache.php');
		}
		else
		{
			$smarty->assign('name', $name);
			$smarty->assign('error', '用户不存在！');
			$smarty->display('login.html');
		}
	}
	else
		$smarty->display('login.html');

}
else
{
	$smarty->display('login.html');
}
?>