<?php 
	require("conn/conn.php");
	require("Transfer_strategy.php");
	echo "<script language=\"javascript\">
setTimeout(\"location.href='if_need_transfer.php'\",300000)
</script>";//10����ˢ��һ�Σ���10����ȥ��ѯ�������ݿ⣬���Ƿ�����ҪǨ�Ƶ��ļ�������
echo "<script language=\"javascript\">
function closewindow()
{
    window.close();
}
</script>
<form><input type=\"button\" onClick=\"closewindow()\" value=\"ֹͣ����Ǩ�Ƽ��\"></form>
";
if($_GET["flag"]==1)
{
$fp=fopen("flag.txt","w");
fwrite($fp,$_GET["flag"]);
fclose($fp);
}
//���ж��Ƿ���Ǩ���������ڽ��У�����еĻ����������������û�У���鿴�Ƿ���ҪǨ�ơ�

$fp=fopen("flag.txt","r");
$flag=fread($fp,filesize("flag.txt"));
echo "flag:".$flag;
fclose($fp);
if($flag==1)
{//˵������Ҫ�鿴�Ƿ�Ǩ��
	$sql_queue="select * from QUEUE where QUEUENAME='transfer'";
	$result=mysql_query($sql_queue,$conne->getconnect());
	$statusmenu=mysql_fetch_array($result);
	$status=$statusmenu["STATUS"];
	if($status=='0') //Ϊ0ʱ��ʾû��������Ǩ��
	{	
	
		$sql="select * from ip_table where status='file'  and transfer_status='0' order by loading desc";
		$res=mysql_query($sql,$conne->getconnect());
		$nums=mysql_num_rows($res);
	//Ѱ����ҪǨ�Ƶ��ļ�������,����Դ��������Ŀ���������ip��ַд��transfer_ip.txt�У�������ʾ����ԱǨ���ļ�
		
			$menu=mysql_fetch_array($res);
			if(selectsourceserver($menu["loading"])=="need") //ѡ��ԭ���ݷ�����
			{
				$fp=fopen("transfer_ip.txt","w");
				fwrite($fp,$menu["ip_address"]."\n");
				
				$sql1="select * from ip_table where status='manager'";
				$res1=mysql_query($sql1,$conne->getconnect());
				$menu1=mysql_fetch_array($res1);
				$managerip=$menu1["ip_address"];
				//���Ҹ���������
				 $url = "http://".$managerip."/configmanager/ha_server_ip.php?serverip=".$menu["ip_address"];
				 $ch = curl_init($url) ; 
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		         curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		         $ha_server_ip = curl_exec($ch) ; 
		         curl_close($ch);
				$ip=selecttargetserver($conne->getconnect(),$ha_server_ip);
				
				fwrite($fp,$ip);
				fclose($fp);
				$fp=fopen("flag.txt","w");
				$flag=fwrite($fp,'0');
				fclose($fp);
				//�ж��Ƿ�����Ǩ������
				$userid=selectuser($menu["ip_address"]); //�����ʺϵ��û�id��
				if($userid=='0')
				{
					$fp=fopen("/var/log/csc/stage.txt","a");
					fwrite($fp,$stage."\n");
					fwrite($fp,"only one user,needn't transfer!"."\n");
					fclose($fp);
					exit();
				}
				echo"<script language='javascript'>
					  if(confirm(\"��Ҫ�����ļ�Ǩ��,source server:".$menu["ip_address"].",target server:".$ip."��ȷ��ҪǨ���ļ���\")) 
			{//�����true ����ô�Ͱ�ҳ��ת��
				location.href=\"sendtransferip.php?flag=1\"; 
			} 
			else 
			{//���� 
				alert(\"��ȡ����Ǩ���ļ�\"); 
			} 
		
					</script>";
					
		}
	}
}
else if($flag=='2'){
//˵����Ǩ�����
		$fp=fopen("flag.txt","w");
		$flag=fwrite($fp,'1');
		fclose($fp);
		echo"<script language='javascript'>
			if(alert('Ǩ����ɣ��뷵��'))
			{
			location.href='minitoring.php';
			}	
					</script>";

}

?>