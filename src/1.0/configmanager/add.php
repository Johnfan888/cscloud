<?php
	session_start();
	header('Content-Type:text/html;charset=gb2312');
	if($_SESSION['name']=="")
	{
	echo "��δ��½��";
	exit();
	
	}
?>
<?php 
require("conn/conn.php");
$ip1=$_POST["ip"];
$ip2=$_POST["ip2"];//--------ip��Χ
$use=$_POST["use"];//--------��;�����������ã�
$status=$_POST["status"];
$cpu=$_POST["cpu"];
$memory=$_POST["memory"];
$disk=$_POST["disk"];
$userfilepath=$_POST["userfilepath"];
//---------ip��Χ----------
$ip = array();//���е�ip
if($ip2!=""){
$ip1 = explode('.',$ip1); 
$ip_start= (int)$ip1[3];//ip��ʼ
$ip2 = explode('.',$ip2); 
$ip_end= (int)$ip2[3];//ip����

array_pop($ip1);//ȥ��ip���һ��
$ip3=implode(".", $ip1);//ipǰ3��

for($i=$ip_start;$i<=$ip_end;$i++){
	$ip[]=$ip3.".".$i;
}
}
else{
	$ip[]=$ip1;
}
//---------��;-----
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
		 echo"<script language='javascript'>if(confirm('POST_SIZE��д�����뷵�أ�')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
	}
	else
	{
		$post_size=$_POST["postsize"]*1000000;
	}

}
$strlen=strlen($userfilepath);
$array=str_split($userfilepath);
if($array[0]!='/')//$userfilepathδ��"/"��ͷ
{
	$str="/".$str;
}
if($array[$strlen-1]!='/')//$userfilepathδ��"/"����
{
	$str=$str."/";
}

//---����ȡip--
$num = count($ip);        //count��÷ŵ�for���棬�����ú���ִֻ��һ��
for ($j=0;$j<=$num;$j++){
$add_ip=$ip[$j];	
//���жϸ�ip�Ƿ��Ѿ�����
if($add_ip=="")
{
 echo"<script language='javascript'>if(confirm('ip��ַ����Ϊ�գ�')){location.href='addnewserver.php';}else{location.href='addnewserver.php';}</script>";

}
else{
		$sq="select * from ".$table." where ip_address='".add_ip."'";
		$res=mysql_query($sq,$conne->getconnect());
		$nums=mysql_num_rows($res);
		if($nums>5)
		{
					 echo"<script language='javascript'>if(confirm('��ip��ַ�Ѿ����ã��뷵�ز鿴��')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
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
						 echo"<script language='javascript'>if(confirm('��Ⱥϵͳ��ֻ����һ̨manager����ipΪ��".$menu["ip_address"]."���뷵�أ�')){location.href='addnewserver.php';}else{location.href='addnewserver.php';}</script>";
						}
					   else
					   {
							$sql="insert into ".$table." values(NULL,'".$add_ip."','manager','".$cpu."','".$memory."','".$disk."','".$userfilepath."','".$post_size."',NULL,NULL,'0')";
						  mysql_query($sql,$conne->getconnect());
						  //echo "2";
						  echo"<script language='javascript'>if(confirm('��ӳɹ����뷵�أ�')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
					   } 
				}
				else
				{
					 $sql="insert into ".$table." values(NULL,'".$add_ip."','".$status."','".$cpu."','".$memory."','".$disk."','".$userfilepath."','".$post_size."',NULL,NULL,'0')";
					  mysql_query($sql,$conne->getconnect());
					 //echo"3";
						  echo"<script language='javascript'>if(confirm('��ӳɹ����뷵�أ�')){location.href='configserver.php';}else{location.href='configserver.php';}</script>";
					 
				}
		}
}
}
?>
