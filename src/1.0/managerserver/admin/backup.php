<?php
/*
 * 空间信息管理页面
 * author:张程
 */

//载入初始化文件
require(dirname(__FILE__) . "/../includes/init.inc.php");

//定义页面必须验证是否登录
define("AUTH", "TRUE");

//验证是否为管理员
if(empty($_SESSION['admin']))
{
	exit;
}

require('cls_config.php');
$c = new cls_config();
$c->_construct();

if(!empty($_POST['submit']))
{
	$pass=$_POST['pass'];
	$time_interval=$_POST['Time_interval'];
	$need_backup=$_POST["Need_backup"];//"0"表示需要，"1"表示不需要

	$c->_set("Password",$pass);
	$c->_set("Time_interval",$time_interval);
	$c->_set("Need_backup",$need_backup);
	$c->save();
}

//密码
$password = $c->_get("Password");
//双备份
$double = $c->_get("Need_backup");
//时间间隔
$timespan = $c->_get("Time_interval");

$smarty->assign('pwd', $password);
$smarty->assign('double', $double);
$smarty->assign('timepsan', $timespan );

$smarty->display('backup.html');

?>