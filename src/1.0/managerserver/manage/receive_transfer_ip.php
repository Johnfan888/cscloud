<?php
$originip=$_GET["originip"]; //Ǩ�Ƶ�Ԫ���ݷ�����
$targetip=$_GET["targetip"]; //Ǩ�Ƶ�Ŀ�������
$userid=$_GET["userid"]; //Ǩ�Ƶ�Ŀ���û�
$configmanagerip=$_GET["configmanagerip"]; 
$fp=fopen("/var/log/csc/transfer_server.txt","w");
fwrite($fp,"originip=".$originip."\n");
fwrite($fp,"targetip=".$targetip."\n");
fwrite($fp,"configmanagerip=".$configmanagerip."\n");
fclose($fp);
$get_url="http://localhost/manage/transfer.php?stage=1&id=1&userid=".$userid;
		$ch = curl_init($get_url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ;  //ִ��ʱ����һ������ֵ 
			if ($output===FALSE) {//������,д�������־��
			if(!is_file("/var/log/csc/transfer_log.txt"))
			{
			 $fp=fopen("/var/log/csc/transfer_log.txt","w");
			  fclose($fp);
			}
			 $fp=fopen("/var/log/csc/transfer_log.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		");
			   fwrite($fp,"transfer  ");
			   fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			   fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);



 ?>