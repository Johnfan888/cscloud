<?php
	$serverip=$_GET["serverip"];
	require_once("../include/comment.php"); 
	require_once("../include/user.class.php"); 

	$tt =&username::getInstance();
	//require_once ('/srv/www/htdocs/includes/init.inc.php');	
	//������ļ���������ֻ��һ���û�����Ǩ��
	//$sql="select count(*) from filesize where serverip='".$originip."'";  //filesize���ݿⲻ���ڣ���
	$sql="select ha_server_ip from  T_UserZone where server_ip='$serverip'";
	$res=mysql_query($sql,$tt->Con1);
	$array=mysql_fetch_array($res);
	echo $array["ha_server_ip"];

?>