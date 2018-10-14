<?php
/**
 * ISTL 注册页面
 * author: xli
*/

require(dirname(__FILE__) . "/includes/init.inc.php");

//如果是提交页面
//可以正确注册
$num=$argv[1];
for ($x=0; $x<=$num; $x++) {

	$number = rand(1000000, 9999999);
	$passwd="123456";
	$email=$number."@istl.chd.edu.cn";
	$sql = "insert into T_User (email, password,is_checked) values ('{$email}', MD5('{$passwd}'),1)";
	$db->Query($sql);
	$id = $db->InsertID();
	$sql = "insert into T_UserZone (user_id, useable_size) values ('{$id}', '21474836480')";
	$db->Query($sql);
}
?>
