<?php
/*
 * 创建新文件夹
 * author:张程
 */

//定义页面必须验证是否登录
define("AUTH", "TRUE");

//载入初始化文件
require(dirname(__FILE__) . '/includes/init.inc.php');

//载入文件处理助手
require(INC_PATH . '/file.helper.inc.php');

if(!empty($_GET['name']) && !empty($_GET['pid']))
{
	//父目录ID
	$pid = $_GET['pid'];
	//判断请求的父ID是否是文件夹且属于登录人
	$sql = "select * from T_FileInfo where file_id='{$pid}' and file_type=1 and user_id='{$_COOKIE['id']}'";
	$file = $db->FetchAssocOne($sql);
	if($db->NumRowsWithoutSql() > 0)
	{
		//请求文件是合法的
		$new_name = trim($_GET['name']);
		if(!empty($new_name))
		{
			//重命名不为空
			//检查同名目录
			$sql = "select count(1) as num from T_FileInfo where file_name='{$new_name}' and file_type=1 and parent_id='{$pid}' and user_id='{$_COOKIE['id']}'";
			$num = $db->FetchAssocOne($sql);
			if($num['num'] > 0)
			{
				//有同名目录
				echo '{result:false, msg:"该目录已经存在！"}';
			}
			else
			{
				//没有同名目录
				//新建文件夹名
				$unique = UniqueName();

				/*
			    //文件夹路径
				$dirpath = '';
				$dir_id = $pid;
				while($dir_id != '0')
				{
					$sql = "select * from T_FileInfo where file_id='{$dir_id}' and user_id='{$_COOKIE['id']}'";
					$row = $db->FetchAssocOne($sql);

					$dirpath = "{$row['file_id']}/{$dirpath}";
					$dir_id = $row['parent_id'];
				}
				$dirpath .= "{$unique}";
				
				
				//获取存储文件的服务器所有信息
				$sql = "select * from T_FileLocation where file_id ='{$pid}'";
				$info = $db->FetchAssocOne($sql);

				if($info)
				{
					//实际存储ip和地址
					$ip = $info['server_ip'];
					$path = $info['file_path'];

					$urlpath = "{$path}/{$_COOKIE['name']}/{$unique}";

					 //curl请求新建远端服务器文件夹
					 //release时配置
					 //$urll = "http://{$ip}/www/delete.php" ;
					 //debug时配置
					 $url = "http://localhost/istl/fileserver/www/newdir.php" ;
					 $data = array( 'path' => $urlpath);
					 $ch = curl_init();
					 //设定地址
					 curl_setopt($ch, CURLOPT_URL, $url);
					 //将curl_exec()获取的信息以文件流的形式返回，这样就可以解析到json数组
					 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
					 curl_setopt($ch, CURLOPT_POST, 1);
					 curl_setopt($ch, CURLOPT_VERBOSE,1);
					 curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
					 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					 //执行结果，为json格式
					 $result = curl_exec($ch);
					 curl_close($ch);
					 //解析json字符串
					 $json = json_decode($result, true);

					 if (!$json['result']) 
					 {
						 echo '{result:false, msg:"' . $json['msg'] . '"}';
					 }
					else
					{
						//写入数据库T_FileInfo
						$time = time();
						$sql = "insert into T_FileInfo values ('{$unique}', '{$new_name}', '{$pid}', '{$file['file_name']}', '1', '0', '1', '{$time}', '{$_COOKIE['id']}')";
						$db->Query($sql);

						//写入缓存表T_Cache
						$sql = "insert into T_Cache select * from T_FileInfo where file_id='{$unique}'";
						$db->Query($sql);

						echo '{result:true, msg:"新建成功！"}';
					}
					*/

				//写入数据库T_FileInfo
				$time = time();
				$sql = "insert into T_FileInfo values ('{$unique}', '{$new_name}', '{$pid}', '{$file['file_name']}', '1', '0', '1', '{$time}', '{$_COOKIE['id']}', 0)";
				$db->Query($sql);

				//写入缓存表T_Cache
				$sql = "insert into T_Cache select file_id, file_name, parent_id, parent_name, version, size, file_type, modify_time, user_id from T_FileInfo where file_id='{$unique}'";
				$db->Query($sql);

				echo '{result:true, msg:"新建成功！"}';
			}
		}
		else
		{
			//新建名为空
			echo '{result:false, msg:"目录名不能为空！"}';
		}
	}
	else
	{
		//请求文件不属于本人
		echo '{result:false, msg:"错误的请求！"}';
	}
}
?>