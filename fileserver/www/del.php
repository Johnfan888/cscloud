<?php
	require("configure_class.php");
	$c = new Configuration();
	$c->_construct();
	$webserverip=$c->_get("ManagerServerIP");
	$webserverip=trim($webserverip);
	$serverid=$c->_get("ServerID");
		$delfile =$_GET["dir"];
		$user = $_GET['owner'];
		
	//��¼��־---------------------------------------------	
		$fp=fopen("/var/log/csc/data_log.txt","a");
		fwrite($fp,date("Y-m-d H:i:s")."		");
		fwrite($fp,"delete		");
		fwrite($fp,$delfile."		");
		fwrite($fp,$_GET['owner']."		");
		fwrite($fp,$serverid."\n");
		fclose($fp);	
	//--------------------------------------------	
		
		
		if(filetype($delfile) == "dir") {
			$res = rmdir($delfile);
			
		}else{
			$res = unlink($delfile);
					}
		if($res) {
			
			//���»���û����̵Ĵ�С-------------------------------------------------
			  function countDirSize($dir)
				  {
						 $handle = opendir($dir);
						 while (false!==($FolderOrFile = readdir($handle)))
						 {
						  if($FolderOrFile != "." && $FolderOrFile != "..") 
						  {  
						   if(is_dir("$dir/$FolderOrFile")) { 
							$sizeResult += countDirSize("$dir/$FolderOrFile"); 
						   } else { 
							$sizeResult += filesize("$dir/$FolderOrFile"); 
						   }
						  }  
						 }
						 closedir($handle);
						 return $sizeResult;
					} 
				
				  
//���¼����û���ʣ�Ĵ��̿ռ�					 
				$userPath = $userPath .$user; 
				$userDirSize=countDirSize($userPath);
				  
//���¼����û���ʣ�Ĵ��̿ռ�					 
				
				
//--------------------------------------------------------------			
//ת��web������
		$post_url = "http://".$webserverip."/manage/deletefromdb.php" ;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $post_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		$post_data= array( 
			  "id" => $_GET["id"],
			   "owner" =>$_GET["owner"],
			   "serverid" =>$serverid,
			   "dirsize"=>$userDirSize
			  );
		  curl_setopt($ch,CURLOPT_VERBOSE,1);
		  curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$output = curl_exec($ch);
			if ($output===FALSE) {//������,д�������־��
			 $fp=fopen("/var/log/csc/error_log.txt","a");
			  fwrite($fp,"time:".$showtime."   ");
			   fwrite($fp,"delete   ");
			  fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);
		  //echo "<a href=http://".$webserverip."/manage/updown.php>ɾ���ɹ����뷵�أ�</a>";
		  header("Location:http://".$webserverip."/manage/updown.php");
		}else {
			die("ɾ��ʧ�ܣ���ȷ���Ƿ���Ȩ�ޡ�<br />�����Ŀ¼����ȷ��Ŀ¼Ϊ��Ŀ�ա�");
		}
	//}
?>
