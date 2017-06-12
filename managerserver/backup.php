<?php
/*
 * 处理文件备份页面
 * author:张程
 */

//载入初始化文件
require(dirname(__FILE__) . "/includes/init.inc.php");

//载入日志记录助手
require(INC_PATH . '/log.helper.inc.php');

//一次备份所有记录
$sql = "select * from T_FileLocation where flag=0 order by server_ip";
$backups = $db->FetchAssoc($sql);

if($backups)
{
	//获取备份时的key
	require(ROOT_PATH . '/admin/cls_config.php');
	$c = new cls_config();
	$c->_construct();
	$key = $c->_get("Password");

	$ch = curl_init();
	foreach($backups as $row)
	{
		 WriteLog('backup', "start backup file {$row['file_id']}");

		 //逐条备份
		 $data = array('uid' => $row['user_id'], 'path' => rtrim($row['file_path'], '/'), 'id' => $row['file_id'], 'ha_ip' => $row['ha_server_ip'], 'ha_path' => $row['ha_file_path'], 'ip' => $_SERVER['SERVER_ADDR'], 'key' => $key);
		 $url = "http://{$row['server_ip']}/backup.php" ;
		 $curl_opts[CURLOPT_URL] = $url;
		 $curl_opts[CURLOPT_HEADER] = false;
		 $curl_opts[CURLOPT_RETURNTRANSFER] = true;
		 $curl_opts[CURLOPT_POST] = true;
		 $curl_opts[CURLOPT_POSTFIELDS] = $data;
		 $curl_opts[CURLOPT_TIMEOUT] = 0;
		 curl_setopt_array($ch, $curl_opts);
		 $result = curl_exec($ch);

		 $json = json_decode($result, true);
		 if($json['result'])
		 {
			 //备份成功
			 $sql = "update T_FileLocation set flag=1 where file_id='{$row['file_id']}'";
			 $db->Query($sql);
			 echo '备份成功！';
		 }
		 else
		{
			  echo '备份失败！';
		}

		 WriteLog('backup', "backup file {$row['file_id']} {$json['msg']}");
	}

	curl_close($ch);
}
else
{
	echo '无需要备份的文件！';
}
?>