<?php 
	session_start();
	header('Content-Type:text/html;charset=gb2312');
	if($_SESSION['name']=="")
	{
	echo "��δ��½��";
	exit();
	
	}
$ip=$_GET['ip'];
require("conn/conn.php");
$sql="delete from ip_table where ip_address='".$ip."'";
mysql_query($sql,$conne->getconnect());

 echo"<script language='javascript'>if(confirm('ɾ���ɹ����뷵�أ�')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
?>
