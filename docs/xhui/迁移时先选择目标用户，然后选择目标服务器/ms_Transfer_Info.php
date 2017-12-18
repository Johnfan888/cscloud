<?php
require_once("../include/comment.php");
require_once("../include/user.class.php");
$step=$_GET['step'];
$tt=&username::getInstance();
if($step=="1"){
		    $originip=$_GET['originip'];
			$sql="select used_size,user_id from T_UserZone where server_ip='".$originip."' order by used_size desc";
			$result=mysql_query($sql,$tt->Con1);
			$count=mysql_num_rows($result);
			$menu=mysql_fetch_array($result);
			$useable_size=$menu['used_size']; //取得的是第一个，为最大值
			$user_id=$menu['user_id'];
			$fp=fopen("../manage/username.txt","w");  //ms server
			fwrite($fp,$user_id);
			fclose($fp);
			$result=array($count,$user_id,$useable_size);
			echo json_encode($result); //curl调用 使用json编码

			
		

}
if($step=="2"){
	$userid=$_GET['userid'];
	$sql="select ha_server_ip from  T_UserZone where user_id='$userid'";
	$res=mysql_query($sql,$tt->Con1);
	$array=mysql_fetch_array($res);
	echo  $array["ha_server_ip"];
}
if($step=="3"){
 //添加ms更新T_Server数据库
    $ServerIP=$_GET['ServerIP'];
    $UserFilePath=$_GET['UserFilePath'];
    $sql="insert into T_Server  values(NULL, '{$ServerIP}','{$UserFilePath}','1');";
	$result=mysql_query($sql,$tt->Con1);
	if($result){
		echo "0";
	}
	else{
		echo "1";
	}
}
?>
