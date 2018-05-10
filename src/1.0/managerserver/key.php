<?php
/*
* Token check
* author: zfan
*/

require(dirname(__FILE__) . "/includes/init.inc.php");

$key = $_POST['key'];

//Get default key
require(ROOT_PATH . '/admin/cls_config.php');
$c = new cls_config();
$c->_construct();
$default = $c->_get("Password");

if($key == $default)
{
	$result = array('result' => true);
}
else
{
	$result = array('result' => false);
}
echo json_encode($result);
?>
