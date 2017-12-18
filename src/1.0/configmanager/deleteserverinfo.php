<?php 
	session_start();
	if($_SESSION['name']=="")
	{
	echo "ÉÐÎ´µÇÂ½£¡";
	exit();
	
	}
$ip=$_GET['ip'];
require("conn/conn.php");
$sql="delete from ip_table where ip_address='".$ip."'";
mysql_query($sql,$conne->getconnect());

 echo"<script language='javascript'>if(confirm('É¾³ý³É¹¦£¬Çë·µ»Ø£¡')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
?>