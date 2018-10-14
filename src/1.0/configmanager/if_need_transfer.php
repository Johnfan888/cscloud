<?php 
	header('Content-Type:text/html;charset=gb2312');
	require("conn/conn.php");
	require("Transfer_strategy.php");
	require_once('/srv/www/htdocs/configmanager/sendmail/socketsendmail.php');
	echo "<script language=\"javascript\">
	setTimeout(\"location.href='if_need_transfer.php'\",300000)  //刷新
	</script>";//10分钟刷新一次，即10分钟去查询依次数据库，看是否有需要迁移的文件服务器
	echo "<script language=\"javascript\">
	function closewindow()
	{
	    window.close();
	}
	</script>
	<form><input type=\"button\" onClick=\"closewindow()\" value=\"停止负载迁移监控\"></form>
	";

if($_GET["flag"]==1) //迁移时传递值 flag=1
{
	$fp=fopen("flag.txt","w");
	fwrite($fp,$_GET["flag"]);
	fclose($fp);
}
//先判断是否有迁移任务正在进行，如果有的话，不做操作；如果没有，则查看是否需要迁移。

$fp=fopen("flag.txt","r");
$flag=fread($fp,filesize("flag.txt"));//读取
echo "flag:".$flag;
fclose($fp);
if($flag==1)  
{//说明是需要查看是否迁移
	$sql_queue="select * from QUEUE where QUEUENAME='transfer'";
	$result=mysql_query($sql_queue,$conne->getconnect());
	$statusmenu=mysql_fetch_array($result);
	$status=$statusmenu["STATUS"];
	if($status=='1'){
		echo "<script language='javascript'>alert(\"存在服务器迁移数据，请等待此次迁移完成后迁移其它数据！\");</script>";
	}
	if($status=='0') //为0时表示没有数据在迁移
	{	
			$max=max;
			$transfer=new TransferServer;
			$load=$transfer->LoadExceed();
			if($load == 'loadexceed'){
				echo "<script>alert('系统整体负载过重，请添加服务器！')</script>";
				//发送邮件给管理员
				$mail=new mail();
				$data='数据服务器整体负载过重，请及时添加新的数据服务器';
				$mail->send("配置新服务器",$data);
			   
			}		
			else{
			$maxvalue=$transfer->FindTransferServer($max);
			$menu["ip_address"]=$maxvalue[0];
			$menu["loading"]=$maxvalue[1];
			if(selectsourceserver($menu["loading"])=="need") //选择源数据服务器
			{
				$fp=fopen("transfer_ip.txt","w");
				fwrite($fp,$menu["ip_address"]."\n");
				//查找目标服务器
				$min=min;
				$minvalue=$transfer->FindTransferServer($min); //此处不判断副本服务器
				$ip=$minvalue[0]; //目标服务器
				$hostid=$minvalue[2]; //add
				//获取目标服务器的总容量 byte
				$totalsize=$transfer->TargetServerTotalSize($hostid); //add
				//echo "totalsize".$totalsize;
				//获取目标服务器已使用容量  byte
				$usedsize=$transfer->TargetServerUsedSize($hostid); //add
				//echo "usedsize".$usedsize;
				fwrite($fp,$ip."\n");
				$sql1="select ip_address from ip_table where status='manager'";
				$res1=mysql_query($sql1,$conne->getconnect());
				$menu1=mysql_fetch_array($res1);
				$managerip=$menu1["ip_address"];
				//获取迁移负载值
				//获取负载值
				$c = new Configuration();
				$c->configFile="/srv/www/htdocs/configmanager/config/config.txt";
				$c->_construct();
				$threshold=$c->_get("Loading_threshold");	
				$step=1;//查找适合的用户id；
				$url="http://".$managerip."/manage/ms_Transfer_Info.php?originip=".$menu["ip_address"]."&step=".$step."&targetip=".$ip."&totalsize=".$totalsize."&usedsize=".$usedsize."&threshold=".$threshold;
				$ch=curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
				$result1=curl_exec($ch);  //获得要迁移的用户id
				curl_close($ch); 
			    $result1=json_decode($result1);
				$count=$result1[0];
				$userid=$result1[1];
				$flag=$result1[3];
				//获取到用户使用的值
				//$userusedsize=$result1[2];  //add
				
				fwrite($fp, $userid."\n");
				fclose($fp);
				if($count == 1)	//检查是否符合迁移条件，不能为一个用户;
				{
					$originip1=$menu['ip_address'];
						echo"<script language='javascript'>
					  	if(!confirm(\"服务器".$menu["ip_address"]."只有一个用户，迁移可能导致目标服务器负载过重，确定迁移？\")) 
						{//如果是true ，那么就把页面转向  (此处没有获取到目标服务器和目标用户)   
							//window.close(); 
							location.href=\"cancle_oneuser_transfer.php?step=1&originip1=".$originip1."\";
								 
						} 
						</script>";
				}
				
				//if($userid==""){
				if($userid=="" && $flag==2){
					$originip1=$menu['ip_address'];
					echo "<script language='javascript'>alert(\"服务器".$originip1."负载过重,但不存在用户可将数据迁移到目标服务器  ".$ip." ,请及时添加服务器！\");
					location.href=\"cancle_oneuser_transfer.php?step=2&originip1=".$originip1."\";
					</script>";
					
				}
				if($userid=="" && $flag=='1'){
					$originip1=$menu['ip_address'];
					echo "<script language='javascript'>alert(\"服务器".$originip1."负载过重,但源服务器上所有的用户都会导致目标服务器超过迁移阈值,请及时添加服务器！\");
					location.href=\"cancle_oneuser_transfer.php?step=3&originip1=".$originip1."\";
					</script>";
				}
		         $fp=fopen("flag.txt","w");
				 $flag=fwrite($fp,'0');  //迁移过程中为刷新此值刷新为0
				 fclose($fp);
			
				echo"<script language='javascript'>
					  if(confirm(\"需要进行文件迁移,source server:".$menu["ip_address"].",target server:".$ip."userid:".$userid."。确定要迁移文件吗？\")) 
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
}
?>
