<?php 			
	require("conn/conn.php");
	require("Transfer_strategy.php");
	require_once('/srv/www/htdocs/configmanager/sendmail/socketsendmail.php'); 
	
//先判断是否有迁移任务正在进行，如果有的话，不做操作；如果没有，则查看是否需要迁移。
	$sql_queue="select * from QUEUE where QUEUENAME='transfer'";
	$result=mysql_query($sql_queue,$conne->getconnect());
	$statusmenu=mysql_fetch_array($result);
	$status=$statusmenu["STATUS"];
	if($status=='0')
	{		
			//获取负载最大的服务器(zabbix API)
			$max=max;
			$transfer=new TransferServer;
			$load=$transfer->LoadExceed(); //找到负载最轻的服务器
			if($load == 'loadexceed'){
				//发送邮件给管理员
				$mail=new mail();
				$data='集群系统整体负载过重，请及时添加新的数据服务器';
				$mail->send("配置新服务器",$data);
			   
			}
			else{
				$maxvalue=$transfer->FindTransferServer($max);
				$menu["ip_address"]=$maxvalue[0];
				$menu["loading"]=$maxvalue[1];
			if(selectsourceserver($menu["loading"])=="need")
			{
				$fp=fopen("/srv/www/htdocs/configmanager/transfer_ip.txt","w");
				fwrite($fp,$menu["ip_address"]."\n");
				//查找ms的ip地址
				$sql1="select * from ip_table where status='manager'";
				$res1=mysql_query($sql1,$conne->getconnect());
				$menu1=mysql_fetch_array($res1);
				$managerip=$menu1["ip_address"];  //ms服务器地址 (采用轮询的方式)
				$step=1;//查找适合的用户id；
				$url="http://".$managerip."/manage/ms_Transfer_Info.php?originip=".$menu["ip_address"]."&step=".$step;
				$ch=curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
				$result1=curl_exec($ch);  //获得要迁移的用户id
				curl_close($ch);
				$result1=json_decode($result1);
				$count=$result1[0];
				$userid=$result1[1];
				if($count==1)	//检查是否符合迁移条件，不能为一个用户;
				{
					$fp=fopen("/var/log/csc/stage.txt","a");
					fwrite($fp,$stage."\n");
					fwrite($fp,"only one user,needn't transfer!"."\n");
					fclose($fp);
					$mail=new mail();
					$data='服务器'.$menu["ip_address"].'负载过重,但只有一个用户不能进行数据迁移，请及时处理';
					$mail->send("负载过重",$data);
					exit();
				}
				 $step=2; //获取要迁移用户的副本服务器（不能将其迁移到它的副本服务器上）
				 $url = "http://".$managerip."/manage/ms_Transfer_Info.php?userid=".$userid."&step=".$step;
				 $ch = curl_init($url) ; 
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		         curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		         $ha_server_ip = curl_exec($ch) ; 
		         curl_close($ch);
				// $ip=selecttargetserver($conne->getconnect(),$ha_server_ip); //获取目标服务器
				 $min=min;
				 $minvalue=$transfer->FindTransferServer($min,$ha_server_ip);
				 $targetip=$minvalue[0]; //负载最小的服务器
				 fwrite($fp,$targetip."\n");
				 fwrite($fp,$userid."\n");
				 fclose($fp);
				$ip=exec("/bin/sh /srv/www/htdocs/configmanager/getlocalip.sh");
				$array=explode("/",$ip);
				$localip=$array[0];
				$localip=trim($localip,"\'");
			    $url="http://".$localip."/configmanager/sendtransferip.php?flag=1";	 //更新数据库，进行数据的迁移
			    $ch = curl_init($url) ; 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
				$output = curl_exec($ch) ; 
				if ($output===FALSE) 
				{//出错处理,写入错误日志中
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