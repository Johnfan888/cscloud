<?php
/*
 * 处理文件备份时，验证是否是非法上传
 * author:张程
 */

//载入初始化文件
require(dirname(__FILE__) . "/includes/init.inc.php");

$key = $_POST['key'];

//获取备份时的key
require(ROOT_PATH . '/admin/cls_config.php');
$c = new cls_config();
$c->_construct();
$deafult = $c->_get("Password");

if($key = $deafult)
{
	$result = array('result' => true);
}
else
{
	$result = array('result' => false);
}
echo json_encode($result);
?>