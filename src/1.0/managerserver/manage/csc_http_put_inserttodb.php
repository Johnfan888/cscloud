<?php
require("../includes/init.inc.php");
require("../includes/file.helper.inc.php");
require("../includes/user.helper.inc.php");
require("./configure_class.php");
$c = new Configuration();
$c->_construct();
$filename = $_POST['filename'];
$owner = $_POST['owner'];
$serverip = $_POST['serverip'];
$location = $_POST['location'];
$replicaip = $_POST['replicaip'];
$replicalocation = $_POST['replicalocation'];
//add
$dirpath=$_POST['dir_path']; //base path
$size=$_POST['size'];
$version=$_POST['version'];
$time=time();
$fp = fopen("/var/log/csc/adm","a+");
fwrite($fp, "<adm> ".date("Y-m-d H:i:s").": ".$location." uploaded on ".$serverip." replica server:".$replicaip."\n");
fclose($fp);
//insert the info into db
$sql = "insert into T_FileInfo (file_id,version,size,modify_time,user_id) values ('{$filename}', '{$version}', '{$size}', '{$time}', '{$owner}')";
$db->Query($sql);
$sql1="insert into T_FileLocation values('{$filename}', '{$serverip}', '{$dirpath}', '{$replicaip}', '{$replicalocation}', '{$owner}', 0)";
$db->Query($sql1);
//update used Zone;
        $sql2="select used_size from T_UserZone where user_id='{$owner}'";
		$parent = $db->FetchAssocOne($sql2);
		$used=$parent['used_size']+$size;
		$sql3 = "update T_UserZone set used_size='{$used}' where user_id='{$owner}'";
		$db->Query($sql3);

?>