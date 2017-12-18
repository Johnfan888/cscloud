<?php 
function caculate_ordercount()
{
	require_once("../include/comment.php");
	require_once("../include/user.class.php");
	$userr =&username::getInstance();
	//计算cache表的额定容量
	$SQL="select * from tb_file_all";
	$RES=mysql_query($SQL,$userr->Con1);
	$num=mysql_num_rows($RES);//查看tb_file_all中有多少条记录，cache为all的1/4
	$cachenum=ceil($num/4);
	if($cachenum<100)
	{
		$order_num=100;//cache表至少有100条记录
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
	//计算cache表的实际容量
	$sql="select * from tb_file_cache";
	$RES=mysql_query($sql,$user->Con1);
	$real_num=mysql_num_rows($RES);	
	return $real_num;
}


?>
