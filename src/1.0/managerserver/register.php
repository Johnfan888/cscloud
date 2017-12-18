<?php
/**
 * ISTL 注册页面
 * author: 张程
*/

require(dirname(__FILE__) . "/includes/init.inc.php");

//如果是提交页面
if(isset($_POST['submit']))
{
	if(!empty($_POST['reg']))
	{
		$reg = $_POST['reg'];
		if(!empty($reg['email']))
		{
			$email = $reg['email'];
			$smarty->assign('email', $email);

			if(!empty($reg['passwd']))
			{
				$passwd = $reg['passwd'];

				if(!empty($reg['vpasswd']))
				{
					$vpasswd = $reg['vpasswd'];

					if($passwd == $vpasswd)
					{
						if(!empty($reg['code']))
						{
							$code = $reg['code'];
							if($code == $_SESSION['authcode'])
							{
								//可以正确注册
								$sql = "insert into T_User (email, password) values ('{$email}', MD5('{$passwd}'))";
								if($db->Query($sql)>0)
								{
									$id = $db->InsertID();
									$time = time();
									$root = md5(ZONE_ROOT.$id);
									$doc = md5(ZONE_DOC.$id);
									$photo = md5(ZONE_PHOTO.$id);
									$music = md5(ZONE_MUSIC.$id);
									$film = md5(ZONE_FILM.$id);
									$sql = "insert into T_FileInfo values ('{$root}', '我的云盘', '0', '', 1, 0, 1, $time, {$id},0),  ('{$doc}', '我的文档', '0', '', 1, 0, 1, $time, {$id},0), ('{$photo}', '我的相册', '0', '', 1, 0, 1, $time, {$id},0), ('{$music}', '我的音乐', '0', '', 1, 0, 1, $time, {$id},0), ('{$film}', '我的视频', '0', '', 1, 0, 1, $time, {$id},0)";

									if($db->Query($sql)>0)
									{
										$sql = "insert into T_UserZone (user_id, useable_size) values ('{$id}', '21474836480')";
										if($db->Query($sql)>0)
										{
											#跳转到验证email页面
											#header('Location:');
											$smarty->assign('text', '<script>alert("注册成功，等待管理员验证通过！");</script>');
										}
									}
								}
							}
							else
							{
								//验证码输入错误
								$smarty->assign('text', '<script>alert("验证码错误！");</script>');
							}

						}
						else
						{
							//验证码不能为空
							$smarty->assign('text', '<script>alert("验证码不能为空！");</script>');
						}
					}
					else
					{
						//两次密码不一致
						$smarty->assign('text', '<script>alert("两次密码不一致！");</script>');
					}
				}
				else
				{
					//确认密码不能为空
					$smarty->assign('text', '<script>alert("确认密码不能为空！");</script>');
				}
			}
			else
			{
				//密码不能为空
				$smarty->assign('text', '<script>alert("密码不能为空！");</script>');
			}
		}
		else
		{
			//邮箱不能为空
			$smarty->assign('text', '<script>alert("邮箱不能为空！");</script>');
		}
	}
	else
	{
		//注册信息不能为空
	}
}

$smarty->display("register.html");

?>
