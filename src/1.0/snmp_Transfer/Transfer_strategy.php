<?php 
//ѡ����ҪǨ���ļ��ķ�������������ߵģ�
function selectsourceserver($loading)
{
	require_once("configure_class.php");
	$c = new Configuration();
	$c->configFile="/srv/www/htdocs/configmanager/config/config.txt";
	$c->_construct();
	$threshold=$c->_get("Loading_threshold") ;
   if($loading>$threshold)
   {
	return "need";
   }
}
//ѡ������͵ķ�����
function selecttargetserver($con,$haserveerip)
{
	
	
	$sql="select * from ip_table where status='file' and ip_address != '$haserveerip'";
	$res=mysql_query($sql,$con);
	$nums=mysql_num_rows($res);
	for($i=0;$i<$nums;$i++)
	{
	$menu=mysql_fetch_array($res);
	$array[$menu["ip_address"]]=$menu["loading"];
	}
	//print_r($array);
	$key = array_search(min($array), $array);
	return $key;
	
}


function selectuser($originip)  //�����û�
{
	require_once("./include/comment.php"); 
	require_once("./include/user.class.php"); 

	$tt =&username::getInstance();
	//require_once ('/srv/www/htdocs/includes/init.inc.php');	
	//������ļ���������ֻ��һ���û�����Ǩ��
	//$sql="select count(*) from filesize where serverip='".$originip."'";  //filesize���ݿⲻ���ڣ���
	$sql="select count(*) from  T_UserZone where serverip='".$originip."'";
	$res=mysql_query($sql,$tt->Con1);
	$array=mysql_fetch_array($res);
	if($array["count(*)"]==1){
		return 0;
	}
	else{	
		//$sql="select username from filesize where serverip='".$originip."' order by usedsize asc limit 1";
		$sql="select user_id from T_UserZone where server_ip='".$originip."' order by used_size asc limit 1"; //�鵽��Ϊ�û�id
		$result=mysql_query($sql,$tt->Con1);
		$menu=mysql_fetch_array($result);
		$fp=fopen("../manage/username.txt","w");
		fwrite($fp,$menu["user_id"]);
		fclose($fp);
		return $menu["user_id"];
	}
}

?>