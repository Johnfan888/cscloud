<?php
/**
 * 公用文件，每一张页面都应该包含本页面
 * author:张程
*/

//设置网站不进行错误警告，在开发和DEBUG时设定为第一个，在发布时，设定为第二个
error_reporting(E_ALL);
#error_reporting(E_ALL^E_NOTICE^E_WARNING);
#ini_set("display_errors", "off");

//开启session，开始时的安全策略是用session，但是用flash上传时，会新产生一个session值代替掉原有的session，故安全策略该位用cookie
session_start();


//设定时区
//date_default_timezone_set('Asia/Shanghai');
date_default_timezone_set('PRC');

//设置所有页面以utf-8的中文编号显示
header("Content-type: text/html;charset=utf-8");

//定义可以包含其他文件
define('INC', 'TRUE');

//定义网站的根目录以及includes文件夹的目录
define('ROOT_PATH', str_replace('/includes/init.inc.php', '', str_replace('\\', '/', __FILE__)));
define('INC_PATH', str_replace('\\', '/', dirname(__FILE__) ) );

//当页面指定必须要进行登录验证时
if(defined("AUTH"))
{
	//检查cookie，判定是否登录，如果未登录，跳转到登录页面
	if (empty($_COOKIE['name']) || empty($_COOKIE['id']))
		header("Location:/login.php");
}

//如果没有config文件，则跳转到安装页面
if(!file_exists(ROOT_PATH . '/includes/config.php'))
{
	header("Location:" . ROOT_PATH . "/install/index.php");
	exit();
}

//包含config配置文件
require(INC_PATH . '/config.php');

//初始化数据库类，在使用时，直接调用$db
require(INC_PATH . '/cls_mysqli.php');
$db = new cls_mysqli;

//初始化smarty类，在使用时，直接调用$smarty
require(ROOT_PATH . '/themes/smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->left_delimiter = '[{';		//修改smarty左占位符
$smarty->right_delimiter = '}]';	//修改smarty右占位符
$smarty->template_dir = ROOT_PATH . '/themes/default';	//修改smarty模板位置
$smarty->cache_dir = ROOT_PATH . '/temp/caches';
$smarty->compile_dir = ROOT_PATH . '/temp/complied';

//初始化个人页面左侧
$smarty->assign('zone_root', $zone_root);
$smarty->assign('zone_doc', $zone_doc);
$smarty->assign('zone_music', $zone_music);
$smarty->assign('zone_photo', $zone_photo);
$smarty->assign('zone_film', $zone_film);

//调用页面安全处理
require(INC_PATH . '/safe.inc.php');

?>
