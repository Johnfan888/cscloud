<?php
require("conn.php");
$filename = $_POST["filename"];
$owner = $_POST["owner"];
$serverip = $_POST["serverip"];
$location = $_POST["location"];
$replicaip = $_POST["replicaip"];
$replicalocation = $_POST["replicalocation"];

$fp=fopen("/var/log/csc/replicate_server","a+");
fwrite($fp, date("Y-m-d H:i:s")." storing owner:$owner file name:$filename from $serverip:$location as local $replicaip:$replicalocation\n");

$dirpath=dirname($replicalocation);
if(!is_dir($dirpath))
        mkdir($dirpath, 0755, "-p");

if(move_uploaded_file($_FILES['upload']['tmp_name'], $replicalocation))
{
	fwrite($fp, "store file $filename successfully\n");
	echo "SUCS";
}
else
{
	$name = $_FILES['upload']['name'];
	$type = $_FILES['upload']['type'];
	$size = $_FILES['upload']['size'];
	$tmp__name = $_FILES['upload']['tmp_name'];
	$error = $_FILES['upload']['error'];
	fwrite($fp, "store $filename failure: $error. file name:$name type:$type size:$size temp name:$tmp_name\n");
	echo "FAIL";
}
fclose($fp);
?>

