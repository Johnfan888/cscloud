<?php
/*
 * 删除文件页面
 * author:张程
 */

//定义页面必须验证是否登录
define("AUTH", "TRUE");

//载入初始化文件
require(dirname(__FILE__) . '/includes/init.inc.php');

//载入日志记录助手
require(INC_PATH . '/log.helper.inc.php');

if(!empty($_GET['id']))
{
	//判断要删除的文件的ID是否是属于登录人的
	$id = $_GET['id'];
	$sql = "select * from T_FileInfo where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
	$file = $db->FetchAssocOne($sql);
	$sql1="select used_size from T_UserZone where user_id='{$_COOKIE['id']}'";
	$usedsize= $db->FetchAssocOne($sql1);
	$used=$usedsize['used_size']-$file['size'];
	//说明请求文件是登陆人的
	if($file)
	{
		if($file['file_type'] == 1)
		{
			//删除文件夹
			//判断该ID下是否不为空(文件夹)
			$sql = "select count(1) as num from T_FileInfo where parent_id='{$id}' and user_id='{$_COOKIE['id']}'";
			$num = $db->FetchAssocOne($sql);
			if($num['num'] > 0)
			{
				//说明是一个文件夹且目录下还有内容
				echo '{result:false, msg:"该目录下还有内容，不允许删除！"}';
				exit;
			}
			else
			{
				//可以删除
				$sql = "delete from T_FileInfo where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
				$db->Query($sql);
				
				$sql = "delete from T_Cache where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
				$db->Query($sql);

				echo '{result:true, msg:"删除成功！"}';
				exit;
			}
		}
		else
		{
			//删除文件
			//查看该文件是否是同一父目录下的第一版本，如果是且还有其他版本，让is_del字段为1，若不是直接删除
			if($file['version'] == 1)
			{
				//查看是否除了第一版本还有高版本
				$sql = "select * from T_FileInfo where file_name='{$file['file_name']}' and parent_id='{$file['parent_id']}' and user_id='{$_COOKIE['id']}' and version > 1";
				$all = $db->FetchAssocOne($sql);

				//说明有高版本文件
				if($all)
				{
					//将is_del字段置为1
					$sql = "update T_FileInfo set is_del=1 where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
					$db->Query($sql);

					//删除缓存表信息
					$sql = "delete from T_Cache where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
					$db->Query($sql);
				    $sql = "update T_UserZone set used_size='{$used}' where user_id='{$_COOKIE['id']}'";
					$db->Query($sql);
				}
				else
				{
					//没有高版本文件，可以直接删除
					//获取存储文件的服务器所有信息
					$sql = "select * from T_FileLocation where file_id ='{$id}'";
					$info = $db->FetchAssocOne($sql);

					if($info)
					{
						//实际存储ip和地址
						$ip = $info['server_ip'];
						$path = $info['file_path'];

						$path= rtrim($path, '/');

						 //curl请求删除远端服务器文件
						 $url = "http://{$ip}/delete.php" ;
						 $data = array('path' => $path, 'id' => $info['file_id'], 'uid' => $_COOKIE['id']);

						 $ch = curl_init();
						 $curl_opts[CURLOPT_URL] = $url;
						 $curl_opts[CURLOPT_HEADER] = false;
						 $curl_opts[CURLOPT_RETURNTRANSFER] = true;
						 $curl_opts[CURLOPT_FOLLOWLOCATION] = true;
						 $curl_opts[CURLOPT_POST] = true;
						 $curl_opts[CURLOPT_POSTFIELDS] = $data;
						 $curl_opts[CURLOPT_TIMEOUT] = 30;
						 curl_setopt_array($ch, $curl_opts);

						 //执行结果，为json格式
						 $result = curl_exec($ch);
						 curl_close($ch);
						 //解析json字符串
						 $json = json_decode($result, true);
						 if (!$json['result']) 
						 {
							 //删除失败
							 WriteLog('delete', $json['msg']);
							 echo '{result:false, msg:"删除失败！"}';
							 exit;
						  }
						 else
						 {
							if($info['flag'] == 1)
							{
								 //删除副本
								 //副本存储ip和地址
								 $ip = $info['ha_server_ip'];
								 $path = $info['ha_file_path'];

								 $path = rtrim($path, '/');
								 $urlpath = "{$path}/{$_COOKIE['id']}/{$info['file_id']}";

								 //curl请求删除远端服务器副本文件
								 $url = "http://{$ip}/delete.php" ;
								 $data = array('path' => $path, 'id' => $info['file_id'], 'uid' => $_COOKIE['id']);

								 $ch = curl_init();
								 $curl_opts[CURLOPT_URL] = $url;
								 $curl_opts[CURLOPT_HEADER] = false;
								 $curl_opts[CURLOPT_RETURNTRANSFER] = true;
								 $curl_opts[CURLOPT_FOLLOWLOCATION] = true;
								 $curl_opts[CURLOPT_POST] = true;
								 $curl_opts[CURLOPT_POSTFIELDS] = $data;
								 $curl_opts[CURLOPT_TIMEOUT] = 30;
								 curl_setopt_array($ch, $curl_opts);

								 //执行结果，为json格式
								 $result = curl_exec($ch);
								 curl_close($ch);
								 //解析json字符串
								 $json = json_decode($result, true);
								 if (!$json['result']) 
								 {
									 WriteLog('delete', $json['msg']);
								 }
							}

							//删除数据库信息
							$sql = "delete from T_FileInfo where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
							$db->Query($sql);

							//删除缓存表信息
							$sql = "delete from T_Cache where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
							$db->Query($sql);

							//删除存储文件的服务器的信息
							$sql = "delete from T_FileLocation where file_id='{$id}'";
							$db->Query($sql);
							
							//更新用户已用空间信息
						//	$sql = "update T_UserZone set used_size='{$json['used']}' where user_id='{$_COOKIE['id']}'";
						    $sql = "update T_UserZone set used_size='{$used}' where user_id='{$_COOKIE['id']}'";
							$db->Query($sql);
						 }
					}
					//服务器不存在该文件，发生数据冗余
					else
					{
						WriteLog('delete', '要删除的文件不存在，可能发生数据冗余！');
						echo '{result:false, msg:"错误的文件！"}';
					}
				}				
				echo '{result:true, msg:"成功删除文件！"}';
			}
			else
			{
				//删除高版本，直接删除该文件即可
				//获取存储文件的服务器所有信息
				$sql = "select * from T_FileLocation where file_id='{$id}'";
				$info = $db->FetchAssocOne($sql);

				if($info)
				{
					//实际存储ip和地址
					$ip = $info['server_ip'];
					$path = $info['file_path'];

					$path= rtrim($path, '/');

					 //curl请求删除远端服务器文件
					 $url = "http://{$ip}/delete.php" ;
					 $data = array('path' => $path, 'id' => $id, 'uid' => $_COOKIE['id']);

					 $ch = curl_init();
					 $curl_opts[CURLOPT_URL] = $url;
					 $curl_opts[CURLOPT_HEADER] = false;
					 $curl_opts[CURLOPT_RETURNTRANSFER] = true;
					 $curl_opts[CURLOPT_FOLLOWLOCATION] = true;
					 $curl_opts[CURLOPT_POST] = true;
					 $curl_opts[CURLOPT_POSTFIELDS] = $data;
					 $curl_opts[CURLOPT_TIMEOUT] = 30;
					 curl_setopt_array($ch, $curl_opts);

					 //执行结果，为json格式
					 $result = curl_exec($ch);
					 curl_close($ch);
					 //解析json字符串
					 $json = json_decode($result, true);
					 if (!$json['result']) 
					 {
						 WriteLog('delete', $json['msg']);
						 echo '{result:false, msg:"' . $json['msg'] . '"}';
						 exit;
					 }
					else
					{
						if($info['flag'] == 1)
						{
							//删除副本
							//副本存储ip和地址
							$ip = $info['ha_server_ip'];
							$path = $info['ha_file_path'];

							$path= rtrim($path, '/');
							$urlpath = "{$path}/{$_COOKIE['id']}/{$id}";

							 //curl请求删除远端服务器副本文件
							 $url = "http://{$ip}/delete.php" ;
							 $data = array('path' => $path, 'id' => $info['file_id'], 'uid' => $_COOKIE['id']);

							 $ch = curl_init();
							 $curl_opts[CURLOPT_URL] = $url;
							 $curl_opts[CURLOPT_HEADER] = false;
							 $curl_opts[CURLOPT_RETURNTRANSFER] = true;
							 $curl_opts[CURLOPT_FOLLOWLOCATION] = true;
							 $curl_opts[CURLOPT_POST] = true;
							 $curl_opts[CURLOPT_POSTFIELDS] = $data;
							 $curl_opts[CURLOPT_TIMEOUT] = 30;
							 curl_setopt_array($ch, $curl_opts);

							 //执行结果，为json格式
							 $result = curl_exec($ch);
							 curl_close($ch);
							 //解析json字符串
							 $json = json_decode($result, true);
							 if (!$json['result']) 
							 {
								 WriteLog('delete', $json['msg']);
								 echo '{result:false, msg:"' . $json['msg'] . '"}';
								 exit;
							}
						}

						//删除数据库信息
						$sql = "delete from T_FileInfo where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
						$db->Query($sql);

						//删除缓存表信息
						$sql = "delete from T_Cache where file_id='{$id}' and user_id='{$_COOKIE['id']}'";
						$db->Query($sql);

						//删除存储文件的服务器的信息
						$sql = "delete from T_FileLocation where file_id='{$id}'";
						$db->Query($sql);
						
						//更新用户已用空间信息
						    $sql = "update T_UserZone set used_size='{$used}' where user_id='{$_COOKIE['id']}'";
							$db->Query($sql);

						//再查看是否还有其他高版本文件，如果没有其他高版本文件，只剩下一个第一版本，且第一版本的状态is_del=1，那么就接着删除第一版本，否则继续保留第一版本
						$sql = "select * from T_FileInfo where file_name='{$file['file_name']}' and parent_id='{$file['parent_id']}' and user_id='{$_COOKIE['id']}' and version > 1 and is_del=0";
						$other = $db->FetchAssocOne($sql);
						if(!$other)
						{
							//说明没有其他的高版本
							//第一版本是否为已经删除状态，若是，将其删除
							$sql = "select * from T_FileInfo where file_name='{$file['file_name']}' and parent_id='{$file['parent_id']}' and user_id='{$_COOKIE['id']}' and version=1 and is_del=1";
							$base = $db->FetchAssocOne($sql);

							$sql = "select * from T_FileLocation where file_id='{$base['file_id']}'";
							$info = $db->FetchAssocOne($sql);

							if($info)
							{
								//实际存储ip和地址
								$ip = $info['server_ip'];
								$path = $info['file_path'];

								$path= rtrim($path, '/');

								 //curl请求删除远端服务器文件
								 $url = "http://{$ip}/delete.php" ;
								 $data = array('path' => $path, 'id' => $base['file_id'], 'uid' => $_COOKIE['id']);

								 $ch = curl_init();
								 $curl_opts[CURLOPT_URL] = $url;
								 $curl_opts[CURLOPT_HEADER] = false;
								 $curl_opts[CURLOPT_RETURNTRANSFER] = true;
								 $curl_opts[CURLOPT_FOLLOWLOCATION] = true;
								 $curl_opts[CURLOPT_POST] = true;
								 $curl_opts[CURLOPT_POSTFIELDS] = $data;
								 $curl_opts[CURLOPT_TIMEOUT] = 30;
								 curl_setopt_array($ch, $curl_opts);

								 //执行结果，为json格式
								 $result = curl_exec($ch);
								 curl_close($ch);
								 //解析json字符串
								 $json = json_decode($result, true);
								 if (!$json['result']) 
								 {
									 WriteLog('delete', $json['msg']);
								}
								else
								{
									if($info['flag'] == 1)
									{
										//删除副本
										//副本存储ip和地址
										$ip = $info['ha_server_ip'];
										$path = $info['ha_file_path'];

										$path= rtrim($path, '/');
										$urlpath = "{$path}/{$_COOKIE['id']}/{$base['file_id']}";

										 //curl请求删除远端服务器副本文件
										 $url = "http://{$ip}/delete.php" ;
										 $data = array('path' => $path, 'id' => $info['file_id'], 'uid' => $_COOKIE['id']);

										 $ch = curl_init();
										 $curl_opts[CURLOPT_URL] = $url;
										 $curl_opts[CURLOPT_HEADER] = false;
										 $curl_opts[CURLOPT_RETURNTRANSFER] = true;
										 $curl_opts[CURLOPT_FOLLOWLOCATION] = true;
										 $curl_opts[CURLOPT_POST] = true;
										 $curl_opts[CURLOPT_POSTFIELDS] = $data;
										 $curl_opts[CURLOPT_TIMEOUT] = 30;
										 curl_setopt_array($ch, $curl_opts);

										 //执行结果，为json格式
										 $result = curl_exec($ch);
										 curl_close($ch);
										 //解析json字符串
										 $json = json_decode($result, true);
										 if (!$json['result']) 
										 {
											 WriteLog('delete', $json['msg']);
											 exit;
										}
									}

									//删除数据库信息
									$sql = "delete from T_FileInfo where file_id='{$base['file_id']}' and user_id='{$_COOKIE['id']}'";
									$db->Query($sql);

									//删除缓存表信息
									$sql = "delete from T_Cache where file_id='{$base['file_id']}' and user_id='{$_COOKIE['id']}'";
									$db->Query($sql);

									//删除存储文件的服务器的信息
									$sql = "delete from T_FileLocation where file_id='{$base['file_id']}'";
									$db->Query($sql);
									
									//更新用户已用空间信息
									//$sql = "update T_UserZone set used_size='{$json['used']}' where user_id='{$_COOKIE['id']}'";
						   			 $sql = "update T_UserZone set used_size='{$used}' where user_id='{$_COOKIE['id']}'";
									 $db->Query($sql);
								}
							}
						}
						echo '{result:true, msg:"删除成功！"}';
					}
				}
				//服务器不存在该文件，发生数据冗余
				else
				{
					WriteLog('delete', '要删除的文件不存在，可能发生数据冗余！');
					echo '{result:true, msg:"文件错误！"}';
				}
			}
		}
	}
	else
	{
		//非法请求
		echo '{result:true, msg:"错误的请求！"}';
	}
}
?>