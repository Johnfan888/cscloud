<?php 			
	require("conn/conn.php");
	require("Transfer_strategy.php");
	require_once('/srv/www/htdocs/configmanager/sendmail/socketsendmail.php'); 
	
//���ж��Ƿ���Ǩ���������ڽ��У�����еĻ����������������û�У���鿴�Ƿ���ҪǨ�ơ�
	$sql_queue="select * from QUEUE where QUEUENAME='transfer'";
	$result=mysql_query($sql_queue,$conne->getconnect());
	$statusmenu=mysql_fetch_array($result);
	$status=$statusmenu["STATUS"];
	if($status=='0')
	{		
			//��ȡ�������ķ�����(zabbix API)
			$max=max;
			$transfer=new TransferServer;
			$load=$transfer->LoadExceed(); //�ҵ���������ķ�����
			if($load == 'loadexceed'){
				//�����ʼ�������Ա
				$mail=new mail();
				$data='��Ⱥϵͳ���帺�ع��أ��뼰ʱ�����µ����ݷ�����';
				$mail->send("�����·�����",$data);
			   
			}
			else{
				$maxvalue=$transfer->FindTransferServer($max);
				$menu["ip_address"]=$maxvalue[0];
				$menu["loading"]=$maxvalue[1];
			if(selectsourceserver($menu["loading"])=="need")
			{
				$fp=fopen("/srv/www/htdocs/configmanager/transfer_ip.txt","w");
				fwrite($fp,$menu["ip_address"]."\n");
				
				//����Ŀ�������
				 $min=min;
				 $minvalue=$transfer->FindTransferServer($min);
				 $targetip=$minvalue[0]; //������С�ķ�����
				 $hostid=$minvalue[2]; 
				
				$totalsize=$transfer->TargetServerTotalSize($hostid);
				//��ȡĿ���������ʹ������
				$usedsize=$transfer->TargetServerUsedSize($hostid); 
				fwrite($fp,$targetip."\n");
				
				//����ms��ip��ַ
				$sql1="select * from ip_table where status='manager'";
				$res1=mysql_query($sql1,$conne->getconnect());
				$menu1=mysql_fetch_array($res1);
				$managerip=$menu1["ip_address"];  //ms��������ַ (������ѯ�ķ�ʽ)
				$c = new Configuration();
				$c->configFile="/srv/www/htdocs/configmanager/config/config.txt";
				$c->_construct();
				$threshold=$c->_get("Loading_threshold");
				
				$step=1;//�����ʺϵ��û�id��
				$url="http://".$managerip."/manage/ms_Transfer_Info.php?originip=".$menu["ip_address"]."&step=".$step."&targetip=".$targetip."&totalsize=".$totalsize."&usedsize=".$usedsize."&threshold=".$threshold;;
				$ch=curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
				$result1=curl_exec($ch);  //���ҪǨ�Ƶ��û�id
				curl_close($ch);
				$result1=json_decode($result1);
				$count=$result1[0];
				$userid=$result1[1];
				$flag=$result1[3];
				fwrite($fp,$userid."\n");
			    fclose($fp);
				if($count==1)	//����Ƿ����Ǩ������������Ϊһ���û�;
				{
					$fp=fopen("/var/log/csc/stage.txt","a");
					$time=time('Y-m-d H:i:s');
					fwrite($fp, $time."\n");
					fwrite($fp,"only one user,needn't transfer!"."\n");
					fclose($fp);
					$mail=new mail();
					$data='������'.$menu["ip_address"].'���ع���,��ֻ��һ���û����ܽ�������Ǩ�ƣ��뼰ʱ����';
					$mail->send("���ع���",$data);
					exit();
				}
				if($userid=="" && $flag==2){
					$fp=fopen("/var/log/csc/stage.txt","a");
					$time=time('Y-m-d H:i:s');
					fwrite($fp,$time."\n");
					fwrite($fp,"The ds ".$menu["ip_address"]." overload, but there is no right to migrate users, please pay attention to see."."\n");
					fclose($fp);
					$mail=new mail();
					$data='������'.$menu["ip_address"].'���ع���,���������û��ɽ�����Ǩ�Ƶ�Ŀ�������'.$targetip.',�뼰ʱ���ӷ�����!';
					$mail->send("���ع���",$data);
					exit();
					
				}
				if($userid=="" && $flag==1){
					$fp=fopen("/var/log/csc/stage.txt","a");
					$time=time('Y-m-d H:i:s');
					fwrite($fp,$time."\n");
					fwrite($fp,"The ds ".$menu["ip_address"]." Overloaded, but all users will exceed the threshold causes the target server migration, please add server."."\n");
					fclose($fp);
					$mail=new mail();
					$data="������".$menu["ip_address"]."���ع���,��Դ���������е��û����ᵼ��Ŀ�����������Ǩ����ֵ,�뼰ʱ���ӷ�����";
					$mail->send("���ع���",$data);
					exit();
				}
				/* $step=2; //��ȡҪǨ���û��ĸ��������������ܽ���Ǩ�Ƶ����ĸ����������ϣ�
				 $url = "http://".$managerip."/manage/ms_Transfer_Info.php?userid=".$userid."&step=".$step;
				 $ch = curl_init($url) ; 
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		         curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		         $ha_server_ip = curl_exec($ch) ; 
		         curl_close($ch);*/
				// $ip=selecttargetserver($conne->getconnect(),$ha_server_ip); //��ȡĿ�������
				
				/* $min=min;
				 $minvalue=$transfer->FindTransferServer($min,$ha_server_ip);
				 $targetip=$minvalue[0]; //������С�ķ�����
				 fwrite($fp,$targetip."\n");
				 fwrite($fp,$userid."\n");
				 fclose($fp);*/
				$ip=exec("/bin/sh /srv/www/htdocs/configmanager/getlocalip.sh");
				$array=explode("/",$ip);
				$localip=$array[0];
				$localip=trim($localip,"\'");
			    $url="http://".$localip."/configmanager/sendtransferip.php?flag=1";	 //�������ݿ⣬�������ݵ�Ǩ��
			    $ch = curl_init($url) ; 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
				$output = curl_exec($ch) ; 
				if ($output===FALSE) 
				{//��������,д�������־��
					$fp=fopen("error.txt","w");
					fwrite($fp,date("Y-m-d H:i:s")."		");
					fwrite($fp,"cURL Error:".curl_error($ch)."   ");
					fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
				 } 
				  curl_close($ch);
			}
		}
	}



?>