<?php 
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();
$username=$_GET["username"];
$filename=$_GET["filename"];
$version=$_GET["version"];
 
$sql="select * from tb_file_all where name='".$filename."' and fileowner='".$username."' and version='".$version."'";
$result=mysql_query($sql,$user->Con1);
 $menu=mysql_fetch_array($result);  
 $fileid=$menu["id"];
 
 $sql1="select * from tb_file_location  where id='".$fileid."'";
 $result1=mysql_query($sql1,$user->Con1);
 $menu1=mysql_fetch_array($result1);  
 
$serverip=$menu1["serverip"];
$path=$menu1["locationpath"];
echo $serverip."&".$path."&". $fileid;








?>