<?php
	session_start();
	header('Content-Type:text/html;charset=gb2312');
	if($_SESSION['name']=="")
	{
	echo "尚未登陆！";
	exit();
	
	}
?>
<?php 
require("conn/conn.php");
$ip1=$_POST["ip"];
$ip2=$_POST["ip2"];//--------ip范围
$use=$_POST["use"];//--------用途（立即，备用）
$status=$_POST["status"];
$cpu=$_POST["cpu"];
$memory=$_POST["memory"];
$disk=$_POST["disk"];
$userfilepath=$_POST["userfilepath"];
//---------ip范围----------
$ip = array();//所有的ip
if($ip2!=""){
$ip1 = explode('.',$ip1); 
$ip_start= (int)$ip1[3];//ip开始
$ip2 = explode('.',$ip2); 
$ip_end= (int)$ip2[3];//ip结束

array_pop($ip1);//去掉ip最后一段
$ip3=implode(".", $ip1);//ip前3段

for($i=$ip_start;$i<=$ip_end;$i++){
	$ip[]=$ip3.".".$i;
}
}
else{
	$ip[]=$ip1;
}
//---------用途-----
if ($use==1){
	$table="ip_table";
}
else{
	$table="spare_node";
}
//-----------------
if($_POST["postsize"]==0||$_POST["postsize"]=="")
{
	$post_size=1000000000;
}
else
{
	if($_POST['postsize']<0)
	{
		 echo"<script language='javascript'>if(confirm('POST_SIZE填写有误！请返回！')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
	}
	else
	{
		$post_size=$_POST["postsize"]*1000000;
	}

}
$strlen=strlen($userfilepath);
$array=str_split($userfilepath);
if($array[0]!='/')//$userfilepath未以"/"开头
{
	$str="/".$str;
}
if($array[$strlen-1]!='/')//$userfilepath未以"/"结束
{
	$str=$str."/";
}

//---遍历取ip--
$num = count($ip);        //count最好放到for外面，可以让函数只执行一次
for ($j=0;$j<=$num;$j++){
$add_ip=$ip[$j];	
//先判断该ip是否已经配置
if($add_ip=="")
{
 echo"<script language='javascript'>if(confirm('ip地址不能为空！')){location.href='addnewserver.php';}else{location.href='addnewserver.php';}</script>";

}
else{
		$sq="select * from ".$table." where ip_address='".add_ip."'";
		$res=mysql_query($sq,$conne->getconnect());
		$nums=mysql_num_rows($res);
		if($nums>5)
		{
					 echo"<script language='javascript'>if(confirm('该ip地址已经配置，请返回查看！')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
		}
		else{
				if($status=="manager")
				{
					   $sql="select * from ".$table." where status='manager'";
					   $result=mysql_query($sql,$conne->conn);
					   $num=mysql_num_rows($result);
					   $menu=mysql_fetch_array($result);
					   if($num==1)
					   {
						 echo"<script language='javascript'>if(confirm('集群系统中只能有一台manager它的ip为：".$menu["ip_address"]."，请返回！')){location.href='addnewserver.php';}else{location.href='addnewserver.php';}</script>";
						}
					   else
					   {
							$sql="insert into ".$table." values(NULL,'".$add_ip."','manager','".$cpu."','".$memory."','".$disk."','".$userfilepath."','".$post_size."',NULL,NULL,'0')";
						  mysql_query($sql,$conne->getconnect());
						  //echo "2";
						  echo"<script language='javascript'>if(confirm('添加成功，请返回！')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
					   } 
				}
				else
				{
					 $sql="insert into ".$table." values(NULL,'".$add_ip."','".$status."','".$cpu."','".$memory."','".$disk."','".$userfilepath."','".$post_size."',NULL,NULL,'0')";
					  mysql_query($sql,$conne->getconnect());
					 //echo"3";
						  echo"<script language='javascript'>if(confirm('添加成功，请返回！')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
					 
				}
		}
}
}
?>
