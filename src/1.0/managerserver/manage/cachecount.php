<?php 
function caculate_ordercount()
{
	require_once("../include/comment.php");
	require_once("../include/user.class.php");
	$userr =&username::getInstance();
	//����cache��Ķ����
	$SQL="select * from tb_file_all";
	$RES=mysql_query($SQL,$userr->Con1);
	$num=mysql_num_rows($RES);//�鿴tb_file_all���ж�������¼��cacheΪall��1/4
	$cachenum=ceil($num/4);
	if($cachenum<100)
	{
		$order_num=100;//cache��������100����¼
	}
	else{
		$order_num=$cachenum;
	}
	return $order_num;
}

function caculate_realcount()
{
	require_once("../include/comment.php");
	require_once("../include/user.class.php");
	$user=&username::getInstance();
	//����cache���ʵ������
	$sql="select * from tb_file_cache";
	$RES=mysql_query($sql,$user->Con1);
	$real_num=mysql_num_rows($RES);	
	return $real_num;
}


?>
