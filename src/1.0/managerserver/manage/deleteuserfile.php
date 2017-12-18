<?php 
$dir=$_GET['dir'];
$id=$_GET['id'];
$serverip=$_GET['serverip'];
$user=$_COOKIE['admin']['username'];


//在数据库中找到该文件的存放在那台文件服务器上，然后将文件的信息递交过去
 header("location:http://".$serverip."/www/index.php?flag=delete&username=".$user."&dir=".$dir."&id=".$id);


?>