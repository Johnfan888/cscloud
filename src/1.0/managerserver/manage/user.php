<?php
if(!$_COOKIE['admin']['user_id']){
	exit();
}
?>
<?php
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();

switch ($user->INCOME['act']){
	case 'user_edit_psw':
		$arr = $user->get_user_info($user->INCOME['user_id']);
		$user->assign('username', $arr['username']);
		$user->assign('user_id', $user->INCOME['user_id']);
		break;
	case 'user_edit_psw_do':
		$record['user_id'] = $user->INCOME['user_id'];
		$record['new_psw'] = $user->INCOME['new_psw'];
		$record['new_psw2'] = $user->INCOME['new_psw2'];
		if($user->update_user_psw($record)){
			$user->msg('恭喜，修改密码成功！', 'user.php');
		}else{
			$user->msg('对不起，无法修改密码', -1);
		}
	break;
	case 'user_edit':
		$user_info = $user->get_user_info($user->INCOME['user_id']);
		$user->assign($user_info);
		//$user->assign('username', $user_info['username']);
		//$user->assign('is_open', $user_info['is_open']);
		//$user->assign('is_admin', $user_info['is_admin']);
	break;
	case 'user_edit_do':
		$user->check_null($user->INCOME['user_id'], '用户ID');
		$record['user_id'] = $user->INCOME['user_id'];
		$record['is_open'] = $user->INCOME['is_open'];
		$record['is_admin'] = $user->INCOME['is_admin'];
		$record['company'] = $user->INCOME['company'];
		$record['name'] = $user->INCOME['name'];
		$record['country'] = $user->INCOME['country'];
		$record['tel'] = $user->INCOME['tel'];
		$record['fax'] = $user->INCOME['fax'];
		$record['size'] = $user->INCOME['size']*1000000;
		if($user->edit_user($record)){
			if($record['size']=='0')
			{
				$user->msg('用户空间不能为0', -1);
			}
			else
			{
				if($record['user_id']==1)//说明是管理员，不允许修改他的开放和管理员权限
				{
					if(($record['is_open']==0) or ($record['is_admin']==0))
					{
					 	$user->msg('管理员权限不能修改！', -1);
					 }
					else
					{
						$user->msg('恭喜，修改用户资料成功！', 'user.php');
					}
				}
				else
				{
					$user->msg('恭喜，修改用户资料成功！', 'user.php');
				}
			}
		}else{
			$user->msg('对不起，无法修改资料', -1);
		}
			/*$user->msg($user->edit_user($record), -1);*/
	break;
	case 'del_user':		
		if($user->del_user($user->INCOME['user_id'])){
			$user->msg('恭喜，删除用户成功！', 'user.php');
		}else{
			$user->msg('对不起，无法删除用户', -1);
		}
	break;
	default:
		$user->assign('user', $user->list_user());
}
$user -> assign("act", $user->INCOME["act"]);
$user -> display("manage/user.html");
?>