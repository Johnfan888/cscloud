<?php
require_once("../include/comment.php");
require_once("../include/user.class.php");
$step=$_GET['step'];
$tt=&username::getInstance();
if($step=="1"){
		    $originip=$_GET['originip'];
		    $targetip=$_GET['targetip'];
		    $totalsize=$_GET['totalsize']; //�ܸ���
		    $usedsize=$_GET['usedsize']; //ʹ�õĸ���
		    $threshold=$_GET['threshold'];//Ǩ�Ƹ���
			$sql="select used_size,user_id from T_UserZone where server_ip='".$originip."' order by used_size desc";
			$result=mysql_query($sql,$tt->Con1);
			$count=mysql_num_rows($result);
			//$menu=mysql_fetch_array($result);
			$sql1="select used_size,user_id,ha_server_ip from T_UserZone where server_ip='".$originip."' and ha_server_ip !='".$targetip."' and used_size>0 order by used_size desc";
			$result1=mysql_query($sql1,$tt->Con1);
			$count1=mysql_num_rows($result1);
			$flag=2;
			while($menu=mysql_fetch_array($result1)){
				  $used_size=$menu['used_size']; //ȡ�õ��ǵ�һ����Ϊ���ֵ
				  $user_id=$menu['user_id'];
				  $comparethreshold=($usedsize+$used_size)/$totalsize;
				  if($user_id !="" && $comparethreshold<$threshold){
				  		$flag=0;
				  		$used_size=$menu['used_size']; //ȡ�õ��ǵ�һ����Ϊ���ֵ
				  		$user_id=$menu['user_id'];
				  		break;
				  }
				  else{
				  		$flag=1;
				  		$user_id="";
				  }
			}
			$fp=fopen("../manage/username.txt","w");  //ms server
			fwrite($fp,$user_id);
			fclose($fp);
			$result=array($count,$user_id,$used_size,$flag);
			echo json_encode($result); //curl���� ʹ��json����

			
		

}
//ѡ��Ǩ���û��ĸ�������������ʱ����Ҫ
if($step=="2"){ 
	$userid=$_GET['userid'];
	$sql="select ha_server_ip from  T_UserZone where user_id='$userid'";
	$res=mysql_query($sql,$tt->Con1);
	$array=mysql_fetch_array($res);
	echo  $array["ha_server_ip"];
}
if($step=="3"){
 //���ms����T_Server���ݿ�
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
