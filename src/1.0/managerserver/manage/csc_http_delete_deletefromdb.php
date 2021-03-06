<?php
/*
* FS RESTFUL API - DELETE (Delete DB)
* author: zfan
*/

require("../includes/init.inc.php");
require("../includes/file.helper.inc.php");
require("../includes/user.helper.inc.php");
require("../includes/log.helper.inc.php");
require("./configure_class.php");

$c = new Configuration();
$c->_construct();
$file_id = $_POST['file_id'];
$filename = $_POST['filename'];
$userid = $_POST['userid'];
$size = $_POST['size'];
$version = $_POST['version'];

$sql = "delete from T_FileInfo where file_id='{$file_id}' and user_id='{$userid}'";
$db->Query($sql);
$sql1 = "delete from T_FileLocation where file_id='{$file_id}' and user_id='{$userid}'";
$db->Query($sql1);
//update used Zone
$sql2="select used_size from T_UserZone where user_id='{$userid}'";
$usedsize = $db->FetchAssocOne($sql2);
$used = $usedsize['used_size'] - $size;
$sql3 = "update T_UserZone set used_size='{$used}' where user_id='{$userid}'";
$db->Query($sql3);

WriteLog('cscHttpDelete', "File {$filename} with ID {$file_id} delete DB successfully!");
$result = array('result' => true, 'msg' => "File {$filename} with ID {$file_id} delete DB successfully!");
echo json_encode($result);
?>

