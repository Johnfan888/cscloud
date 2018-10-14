<?php 			
	require("conn/conn.php");
	require("Transfer_strategy.php");
//先判断是否有迁移任务正在进行，如果有的话，不做操作；如果没有，则查看是否需要迁移。
	$sql_queue="select * from QUEUE where QUEUENAME='transfer'";
	$result=mysql_query($sql_queue,$conne->getconnect());
	$statusmenu=mysql_fetch_array($result);
	$status=$statusmenu["STATUS"];
	if($status=='0')
	{		//没有任务正在进行，寻找需要迁移的文件服务器,并把源服务器和目标服务器的ip地址写入transfer_ip.txt中
		$sql="select * from ip_table where status='file' and transfer_status='0' order by loading desc";
		$res=mysql_query($sql,$conne->getconnect());
		$nums=mysql_num_rows($res);
			$menu=mysql_fetch_array($res);
			if(selectsourceserver($menu["loading"])=="need")
			{
				$fp=fopen("./transfer_ip.txt","w");
				fwrite($fp,$menu["ip_address"]."\n");
				//查找ms的ip地址
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
				//检查是否符合迁移条件，不能为一个用户;
				$userid=selectuser($menu["ip_address"]); //查找适合的用户id；
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



?>