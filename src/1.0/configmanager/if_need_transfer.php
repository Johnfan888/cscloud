<?php 
	header('Content-Type:text/html;charset=gb2312');
	require("conn/conn.php");
	require("Transfer_strategy.php");
	require_once('/srv/www/htdocs/configmanager/sendmail/socketsendmail.php');
	echo "<script language=\"javascript\">
	setTimeout(\"location.href='if_need_transfer.php'\",300000)  //ˢ��
	</script>";//10����ˢ��һ�Σ���10����ȥ��ѯ�������ݿ⣬���Ƿ�����ҪǨ�Ƶ��ļ�������
	echo "<script language=\"javascript\">
	function closewindow()
	{
	    window.close();
	}
	</script>
	<form><input type=\"button\" onClick=\"closewindow()\" value=\"ֹͣ����Ǩ�Ƽ��\"></form>
	";

if($_GET["flag"]==1) //Ǩ��ʱ����ֵ flag=1
{
	$fp=fopen("flag.txt","w");
	fwrite($fp,$_GET["flag"]);
	fclose($fp);
}
//���ж��Ƿ���Ǩ���������ڽ��У�����еĻ����������������û�У���鿴�Ƿ���ҪǨ�ơ�

$fp=fopen("flag.txt","r");
$flag=fread($fp,filesize("flag.txt"));//��ȡ
echo "flag:".$flag;
fclose($fp);
if($flag==1)  
{//˵������Ҫ�鿴�Ƿ�Ǩ��
	$sql_queue="select * from QUEUE where QUEUENAME='transfer'";
	$result=mysql_query($sql_queue,$conne->getconnect());
	$statusmenu=mysql_fetch_array($result);
	$status=$statusmenu["STATUS"];
	if($status=='1'){
		echo "<script language='javascript'>alert(\"���ڷ�����Ǩ�����ݣ���ȴ��˴�Ǩ����ɺ�Ǩ���������ݣ�\");</script>";
	}
	if($status=='0') //Ϊ0ʱ��ʾû��������Ǩ��
	{	
			$max=max;
			$transfer=new TransferServer;
			$load=$transfer->LoadExceed();
			if($load == 'loadexceed'){
				echo "<script>alert('ϵͳ���帺�ع��أ�����ӷ�������')</script>";
				//�����ʼ�������Ա
				$mail=new mail();
				$data='���ݷ��������帺�ع��أ��뼰ʱ����µ����ݷ�����';
				$mail->send("�����·�����",$data);
			   
			}		
			else{
			$maxvalue=$transfer->FindTransferServer($max);
			$menu["ip_address"]=$maxvalue[0];
			$menu["loading"]=$maxvalue[1];
			if(selectsourceserver($menu["loading"])=="need") //ѡ��Դ���ݷ�����
			{
				$fp=fopen("transfer_ip.txt","w");
				fwrite($fp,$menu["ip_address"]."\n");
				//����Ŀ�������
				$min=min;
				$minvalue=$transfer->FindTransferServer($min); //�˴����жϸ���������
				$ip=$minvalue[0]; //Ŀ�������
				$hostid=$minvalue[2]; //add
				//��ȡĿ��������������� byte
				$totalsize=$transfer->TargetServerTotalSize($hostid); //add
				//echo "totalsize".$totalsize;
				//��ȡĿ���������ʹ������  byte
				$usedsize=$transfer->TargetServerUsedSize($hostid); //add
				//echo "usedsize".$usedsize;
				fwrite($fp,$ip."\n");
				$sql1="select ip_address from ip_table where status='manager'";
				$res1=mysql_query($sql1,$conne->getconnect());
				$menu1=mysql_fetch_array($res1);
				$managerip=$menu1["ip_address"];
				//��ȡǨ�Ƹ���ֵ
				//��ȡ����ֵ
				$c = new Configuration();
				$c->configFile="/srv/www/htdocs/configmanager/config/config.txt";
				$c->_construct();
				$threshold=$c->_get("Loading_threshold");	
				$step=1;//�����ʺϵ��û�id��
				$url="http://".$managerip."/manage/ms_Transfer_Info.php?originip=".$menu["ip_address"]."&step=".$step."&targetip=".$ip."&totalsize=".$totalsize."&usedsize=".$usedsize."&threshold=".$threshold;
				$ch=curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
				$result1=curl_exec($ch);  //���ҪǨ�Ƶ��û�id
				curl_close($ch); 
			    $result1=json_decode($result1);
				$count=$result1[0];
				$userid=$result1[1];
				$flag=$result1[3];
				//��ȡ���û�ʹ�õ�ֵ
				//$userusedsize=$result1[2];  //add
				
				fwrite($fp, $userid."\n");
				fclose($fp);
				if($count == 1)	//����Ƿ����Ǩ������������Ϊһ���û�;
				{
					$originip1=$menu['ip_address'];
						echo"<script language='javascript'>
					  	if(!confirm(\"������".$menu["ip_address"]."ֻ��һ���û���Ǩ�ƿ��ܵ���Ŀ����������ع��أ�ȷ��Ǩ�ƣ�\")) 
						{//�����true ����ô�Ͱ�ҳ��ת��  (�˴�û�л�ȡ��Ŀ���������Ŀ���û�)   
							//window.close(); 
							location.href=\"cancle_oneuser_transfer.php?step=1&originip1=".$originip1."\";
								 
						} 
						</script>";
				}
				
				//if($userid==""){
				if($userid=="" && $flag==2){
					$originip1=$menu['ip_address'];
					echo "<script language='javascript'>alert(\"������".$originip1."���ع���,���������û��ɽ�����Ǩ�Ƶ�Ŀ�������  ".$ip." ,�뼰ʱ��ӷ�������\");
					location.href=\"cancle_oneuser_transfer.php?step=2&originip1=".$originip1."\";
					</script>";
					
				}
				if($userid=="" && $flag=='1'){
					$originip1=$menu['ip_address'];
					echo "<script language='javascript'>alert(\"������".$originip1."���ع���,��Դ�����������е��û����ᵼ��Ŀ�����������Ǩ����ֵ,�뼰ʱ��ӷ�������\");
					location.href=\"cancle_oneuser_transfer.php?step=3&originip1=".$originip1."\";
					</script>";
				}
		         $fp=fopen("flag.txt","w");
				 $flag=fwrite($fp,'0');  //Ǩ�ƹ�����Ϊˢ�´�ֵˢ��Ϊ0
				 fclose($fp);
			
				echo"<script language='javascript'>
					  if(confirm(\"��Ҫ�����ļ�Ǩ��,source server:".$menu["ip_address"].",target server:".$ip."userid:".$userid."��ȷ��ҪǨ���ļ���\")) 
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
}
?>
