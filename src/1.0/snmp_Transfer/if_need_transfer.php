<?php 
	require("conn/conn.php");
	require("Transfer_strategy.php");
	echo "<script language=\"javascript\">
setTimeout(\"location.href='if_need_transfer.php'\",300000)
</script>";//10分钟刷新一次，即10分钟去查询依次数据库，看是否有需要迁移的文件服务器
echo "<script language=\"javascript\">
function closewindow()
{
    window.close();
}
</script>
<form><input type=\"button\" onClick=\"closewindow()\" value=\"停止负载迁移监控\"></form>
";
if($_GET["flag"]==1)
{
$fp=fopen("flag.txt","w");
fwrite($fp,$_GET["flag"]);
fclose($fp);
}
//先判断是否有迁移任务正在进行，如果有的话，不做操作；如果没有，则查看是否需要迁移。

$fp=fopen("flag.txt","r");
$flag=fread($fp,filesize("flag.txt"));
echo "flag:".$flag;
fclose($fp);
if($flag==1)
{//说明是需要查看是否迁移
	$sql_queue="select * from QUEUE where QUEUENAME='transfer'";
	$result=mysql_query($sql_queue,$conne->getconnect());
	$statusmenu=mysql_fetch_array($result);
	$status=$statusmenu["STATUS"];
	if($status=='0') //为0时表示没有数据在迁移
	{	
	
		$sql="select * from ip_table where status='file'  and transfer_status='0' order by loading desc";
		$res=mysql_query($sql,$conne->getconnect());
		$nums=mysql_num_rows($res);
	//寻找需要迁移的文件服务器,并把源服务器和目标服务器的ip地址写入transfer_ip.txt中，报警提示管理员迁移文件
		
			$menu=mysql_fetch_array($res);
			if(selectsourceserver($menu["loading"])=="need") //选择原数据服务器
			{
				$fp=fopen("transfer_ip.txt","w");
				fwrite($fp,$menu["ip_address"]."\n");
				
				$sql1="select * from ip_table where status='manager'";
				$res1=mysql_query($sql1,$conne->getconnect());
				$menu1=mysql_fetch_array($res1);
				$managerip=$menu1["ip_address"];
				//查找副本服务器
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
				//判断是否满足迁移条件
				$userid=selectuser($menu["ip_address"]); //查找适合的用户id；
				if($userid=='0')
				{
					$fp=fopen("/var/log/csc/stage.txt","a");
					fwrite($fp,$stage."\n");
					fwrite($fp,"only one user,needn't transfer!"."\n");
					fclose($fp);
					exit();
				}
				echo"<script language='javascript'>
					  if(confirm(\"需要进行文件迁移,source server:".$menu["ip_address"].",target server:".$ip."。确定要迁移文件吗？\")) 
			{//如果是true ，那么就把页面转向
				location.href=\"sendtransferip.php?flag=1\"; 
			} 
			else 
			{//否则 
				alert(\"您取消了迁移文件\"); 
			} 
		
					</script>";
					
		}
	}
}
else if($flag=='2'){
//说明是迁移完成
		$fp=fopen("flag.txt","w");
		$flag=fwrite($fp,'1');
		fclose($fp);
		echo"<script language='javascript'>
			if(alert('迁移完成，请返回'))
			{
			location.href='minitoring.php';
			}	
					</script>";

}

?>