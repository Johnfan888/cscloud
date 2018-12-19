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
for ($x=0; $x<=$num-1; $x++) {

	// #只有当email不重复的时候才行
	$i=1;
	while ( $i == 1) {
	$number = rand(1000000, 9999999);#产生随机数做emal的前面
	$email=$number."@istl.chd.edu.cn";
	
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
	$sql = "insert into T_UserZone (user_id, useable_size) values ('{$id}', '21474836480')";#21474836480是md5加密后的123456，
	$db->Query($sql);



}


$result = array(
	"key" => $key,
	"num" => $num,
	"nu" => $nu);
echo json_encode($result);

?>
