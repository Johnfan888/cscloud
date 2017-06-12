<?php
/*
 * 处理fileserver上传文件curl操作
 * author:张程
 */

//定义页面必须验证是否登录
define("AUTH", "TRUE");

//载入初始化文件
require(dirname(__FILE__) . '/includes/init.inc.php');

//载入用户信息处理助手
require(INC_PATH . '/user.helper.inc.php');

//载入服务器信息处理助手
require(INC_PATH . '/server.helper.inc.php');

if(!empty($_POST['act']))
{
	if($_POST['act'] == 'safe')
	{
		//检查上传
		$pid = $_POST['pid'];
		$uid = $_POST['uid'];
		$fileip = $_POST['ip'];
		$name = $_POST['name'];
		$size = $_POST['size'];

		//判断请求的父ID是否是文件夹且属于登录人
		$sql = "select * from T_FileInfo where file_id='{$pid}' and file_type=1 and user_id='{$uid}'";
		$parent = $db->FetchAssocOne($sql);
		if($db->NumRowsWithoutSql() > 0)
		{
			//合法请求
			//查看用户空间
			$remainsize = RemainSize($uid);
			if($remainsize < $size)
			{
				//空间不够
				$result = array('result' => false, 'msg' => '对不起，您的空间容量不足！');
				echo json_encode($result);
			}
			else
			{
				//获取存储文件服务器IP
				$ip = UserFileIP($uid);
				$ha_ip = UserHAFileIP($uid);
				if($ip != $fileip)
				{
					//请求的文件服务器与为用户分配的服务器不一致，有可能是恶意上传，拒绝
					$result = array('result' => false, 'msg' => '服务器错误！');
					echo json_encode($result);
				}
				else
				{
					$serverinfo = ServerInfo($ip);
					$result = array('result' => true, 'uid' => $uid, 'path' => $serverinfo['file_path']);

					//查询是否已经上传过该文件的第一版本
					$sql = "select file_id from T_FileInfo where file_name='{$name}' and file_type=0 and version=1 and parent_id='{$pid}' and user_id='{$uid}'"; 
					$file = $db->FetchAssocOne($sql);
					if($db->NumRowsWithoutSql() >0)
					{
						$result['base'] = $file['file_id'];
						
						//查询版本
						$sql = "select max(version) as version from T_FileInfo where file_name='{$name}' and parent_id='{$pid}' and file_type=0 and user_id='{$uid}'";
						$version = $db->FetchAssocOne($sql);
						$result['version'] = $version['version'] + 1;
					}
					echo json_encode($result);
				}
			}
		}
		else
		{
			//非法请求
			$result = array('result' => false, 'msg' => '错误的请求！');
			echo json_encode($result);
		}
	}
	else if($_POST['act'] == 'sql')
	{
		//插入数据库操作
		$pid = $_POST['pid'];
		$uid = $_POST['uid'];
		$name = $_POST['name'];
		$version = $_POST['version'];
		$unique = $_POST['id'];
		$size = $_POST['size'];
	//	$used = $_POST['used'];
		$time = time();

		//获取主、副本存储文件服务器IP信息
		$ip = UserFileIP($uid);
		$ha_ip = UserHAFileIP($uid);

		//获取主存储文件服务器路径信息
		$sql = "select * from T_Server where server_ip='{$ip}'";
		$server = $db->FetchAssocOne($sql);
		$path = $server['file_path'];

		//获取副本存储文件服务器路径信息
		$sql = "select * from T_Server where server_ip='{$ha_ip}'";
		$server = $db->FetchAssocOne($sql);
		$ha_path = $server['file_path'];

		//父文件夹信息
		$sql = "select file_name from T_FileInfo where file_id='{$pid}' and file_type=1 and user_id='{$uid}'";
		$parent = $db->FetchAssocOne($sql);

		//插入信息表T_FileInfo
		$sql = "insert into T_FileInfo values ('{$unique}', '{$name}', '{$pid}', '{$parent['file_name']}', '{$version}', '{$size}', '0', '{$time}', '{$uid}', 0)";
		$db->Query($sql);

		//写入缓存表T_Cache
		$sql = "insert into T_Cache select file_id, file_name, parent_id, parent_name, version, size, file_type, modify_time, user_id from T_FileInfo where file_id='{$unique}'";
		$db->Query($sql);

		//插入存储文件的服务器的信息
		$sql = "insert into T_FileLocation values('{$unique}', '{$ip}', '{$path}', '{$ha_ip}', '{$ha_path}', '{$uid}', 0)";
		$db->Query($sql);

		//更新用户已用空间信息
		$sql="select used_size from T_UserZone where user_id='{$uid}'";
		$parent = $db->FetchAssocOne($sql);
		$used=$parent['used_size']+$size;
		$sql = "update T_UserZone set used_size='{$used}' where user_id='{$uid}'";
		$db->Query($sql);

		//上传成功
		$result = array('result' => true, 'msg' => '上传成功！');
		echo json_encode($result);
	}
}
?>