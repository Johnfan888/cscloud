<?php 
require("../include/comment.php");
require("../include/user.class.php");
$user = &username::getInstance();
$filename = $_POST['filename'];
$serverip = $_POST['serverip'];
$location = $_POST['location'];

// write log
$fp = fopen("/var/log/csc/adm","a");
fwrite($fp, "<adm> ".date("Y-m-d H:i:s").": ".$location." deleted on ".$serverip."\n");
fclose($fp);

$sql="delete from adm_file_location where filename='".$filename."'";
mysql_query($sql,$user->Con1);
?>

