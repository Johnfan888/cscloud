<?php 
require("../include/comment.php");
require("../include/user.class.php");
require("./configure_class.php");
$c = new Configuration();
$c->_construct();
$user = &username::getInstance();
$filename = $_POST['filename'];
$owner = $_POST['owner'];
$serverip = $_POST['serverip'];
$location = $_POST['location'];
$replicaip = $_POST['replicaip'];
$replicalocation = $_POST['replicalocation'];

// write log
$fp = fopen("/var/log/csc/adm","a+");
fwrite($fp, "<adm> ".date("Y-m-d H:i:s").": ".$location." uploaded on ".$serverip." replica server:".$replicaip."\n");
fclose($fp);

$sql="insert into adm_file_location values('".$filename."','".$owner."','".$serverip."','".$location."','".$replicaip."','".$replicalocation."','0')";
mysql_query($sql,$user->Con1);
?>

