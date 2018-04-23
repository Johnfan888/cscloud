<?php
/*
* FS RESTFUL API - PUT (Insert DB)
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
$fileserverip = $_POST['fileserverip'];
$filelocation = $_POST['filelocation'];	//Full Path:unused in DB
$fileserverpath = $_POST['fileserverpath']; //base path
$replicaip = $_POST['replicaip'];
$replicapath = $_POST['replicapath'];
$size = $_POST['size'];
$version = $_POST['version'];

$time=time();
$sql = "insert into T_FileInfo (file_id,file_name,version,size,modify_time,user_id) values ('{$file_id}', '{$filename}', '{$version}', '{$size}', '{$time}', '{$userid}')";
$db->Query($sql);
$sql1="insert into T_FileLocation values('{$file_id}', '{$fileserverip}', '{$fileserverpath}', '{$replicaip}', '{$replicapath}', '{$userid}', 0)";
$db->Query($sql1);
//update used Zone
$sql2="select used_size from T_UserZone where user_id='{$userid}'";
$parent = $db->FetchAssocOne($sql2);
$used=$parent['used_size']+$size;
$sql3 = "update T_UserZone set used_size='{$used}' where user_id='{$userid}'";
$db->Query($sql3);

WriteLog('cscHttpPut', "File {$filename} with ID {$file_id} insert DB successfully!");
$result = array('result' => true, 'msg' => "File {$filename} with ID {$file_id} insert DB successfully!");
echo json_encode($result);
?>

