<?php
/**
 * ISTL 注册页面
 * author: xjl
*/

// require(dirname(__FILE__) . "/includes/init.inc.php");
require("../includes/init.inc.php");
require("../includes/file.helper.inc.php");
require("../includes/user.helper.inc.php");
require("../includes/log.helper.inc.php");

//Get key
require(ROOT_PATH . '/admin/cls_config.php');//ROOT_PATH服务器根目录
$c = new cls_config();
$c->_construct();
$key = $c->_get("Password");
//如果是提交页面
//可以正确注册

$num=$_GET['num'];
// $method = $_GET['method'];
// echo gettype($num);
$server_ip=$_GET['server_ip'];
$ha_server_ip=$_GET['ha_server_ip'];


// $num=$argv[1];
// $server_ip=$argv[2];
// $ha_server_ip=$argv[3];
// echo $num,$server_ip,$ha_server_ip;
$user_id = array();
for ($x=0; $x<=$num-1; $x++) {

	// #只有当email不重复的时候才行
	$i=1;
	while ( $i == 1) {
	
	//产生数据位的二进制数
	$number = rand(1, 16777216);//产生十进制数，1~2的24次方
	$number=decbin($number);//十进制转化为二进制
	$sign="00000001";
	//标识位8位+数据位24位，目前标识位全是00000001
	$email=$sign.$number;
	

	$sql="select * from T_User where email='$email' ";
	$nu=$db->NumRows($sql);
	if ($nu>0) {
	$i = 1;
	}
	else{
	$i = 0;
	$status="123";
	}
	
	}
	

	$passwd="123456";
	$sql = "insert into T_User (email, password,is_checked) values ('{$email}', MD5('{$passwd}'),1)";
	$db->Query($sql);

	$id = $db->InsertID();#上一步产生的id
	$sql = "insert into T_UserZone (user_id,useable_size,server_ip,ha_server_ip) values ('{$id}', '21474836480','{$server_ip}','{$ha_server_ip}')";#21474836480是20G
	$db->Query($sql);
	
	
	$user_id[] = $email;
	

	// #指定oid所在ds
	// $sql = "update T_UserZone set server_ip='{$ip}' where user_id='{$uid}'";
	// $db->Query($sql);

	// #指定其所在的副本文件服务器
	// $sql = "update T_UserZone set ha_server_ip='{$ip}' where user_id='{$uid}'";
	// $db->Query($sql);

}
// print_r($user_id); 

$result = array(
	"key" => $key,
	"num" => $num,
	"nu" => $nu,
	"user_id" => $user_id);
echo json_encode($result);

?>
