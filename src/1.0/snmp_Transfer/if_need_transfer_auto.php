<?php 			
	require("conn/conn.php");
	require("Transfer_strategy.php");
//���ж��Ƿ���Ǩ���������ڽ��У�����еĻ����������������û�У���鿴�Ƿ���ҪǨ�ơ�
	$sql_queue="select * from QUEUE where QUEUENAME='transfer'";
	$result=mysql_query($sql_queue,$conne->getconnect());
	$statusmenu=mysql_fetch_array($result);
	$status=$statusmenu["STATUS"];
	if($status=='0')
	{		//û���������ڽ��У�Ѱ����ҪǨ�Ƶ��ļ�������,����Դ��������Ŀ���������ip��ַд��transfer_ip.txt��
		$sql="select * from ip_table where status='file' and transfer_status='0' order by loading desc";
		$res=mysql_query($sql,$conne->getconnect());
		$nums=mysql_num_rows($res);
			$menu=mysql_fetch_array($res);
			if(selectsourceserver($menu["loading"])=="need")
			{
				$fp=fopen("./transfer_ip.txt","w");
				fwrite($fp,$menu["ip_address"]."\n");
				//����ms��ip��ַ
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
				//����Ƿ����Ǩ������������Ϊһ���û�;
				$userid=selectuser($menu["ip_address"]); //�����ʺϵ��û�id��
				if($userid=='0')
				{
					$fp=fopen("/var/log/csc/stage.txt","a");
					fwrite($fp,$stage."\n");
					fwrite($fp,"only one user,needn't transfer!"."\n");
					fclose($fp);
					exit();
				}
				$ip=system("/bin/sh getlocalip.sh");
				$array=explode("/",$ip);
				$localip=$array[0];
				$localip=trim($localip,"\'");
				
				$url="http://".$localip."/configmanager/sendtransferip.php?flag=1";	 //�������ݿ⣬�������ݵ�Ǩ��
				$ch = curl_init($url) ; 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
				$output = curl_exec($ch) ; 
				
				if ($output===FALSE) 
				{//������,д�������־��
					$fp=fopen("error.txt","w");
					fwrite($fp,date("Y-m-d H:i:s")."		");
					fwrite($fp,"cURL Error:".curl_error($ch)."   ");
					fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
				 } 
				  curl_close($ch);
			}
			
	
}



?>