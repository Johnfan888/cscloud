<?php
	session_start();
	if($_SESSION['name']==""){
		echo "��δ��¼";
	}
	require("conn/conn.php");
	$mi_mib=$_GET['mi_mib'];
	$sql="DELETE FROM MinitorItem WHERE mi_mib='$mi_mib'";
	$result=mysql_query($sql,$conne->getconnect());
	if($result){
	echo"<script language='javascript'>alert('ɾ���ɹ���');location.href='minitoritem.php';</script>";
	}
	else{
	echo "<script language='javascript'>alert('ɾ��ʧ��');location.href='minitoritem.php';</script>";
	}
	
?>