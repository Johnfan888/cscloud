<?php 
$dir=$_GET['dir'];
$id=$_GET['id'];
$serverip=$_GET['serverip'];
$user=$_COOKIE['admin']['username'];


//�����ݿ����ҵ����ļ��Ĵ������̨�ļ��������ϣ�Ȼ���ļ�����Ϣ�ݽ���ȥ
 header("location:http://".$serverip."/www/index.php?flag=delete&username=".$user."&dir=".$dir."&id=".$id);


?>