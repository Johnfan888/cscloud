<?php
function download($FolderPath, $FileName)
{
	 header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			//header("Content-Type: application/download");
			// ����ʵ�ļ����ṩ�����������.
			header("Content-Disposition: attachment; filename=$FileName");
			header("Content-Transfer-Encoding: binary");
			//header("Pragma: no-cache");
			readfile($FolderPath);

}

function Postarray($url,$post_data)
{

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$output=curl_exec($ch);
	curl_close($ch);
	return $output;
}


function updatevisittime($FolderPath)
{
//֪ͨmanager���¸��ļ���visittime��Ϣ���������ļ���Ϣ����tb_file_cache
		require("configure_class.php");
		$c = new Configuration();
		$c->_construct();
		$webserverip=trim($c->_get("ManagerServerIP"));
		$visittime=date("Y-m-d H:i:s", fileatime($FolderPath));//��ȡ�ļ��ķ���ʱ��
		$fileid=basename($FolderPath);
		/*$url = "http://".$webserverip."/manage/updatevisittime.php?visittime=".$visittime."&fileid=".$fileid;  
   		$ch = curl_init($url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; */
		$url = "http://".$webserverip."/manage/updatevisittime.php"; 
		$post_data = array (
			   "visittime"=>$visittime,
			   "fileid"=>$fileid
				);
		  $output=Postarray($url,$post_data);//����һ�����ĺ���

}

	clearstatcache();
	$FolderPath=$_GET["dir"];
	$FileName=$_GET["name"];
	if(isset($_GET['oldid']))
	{
		$oldname=$_GET['oldid'];
	}
	else{
		$oldname="";
	}
	$newname=$_GET['id'];
	if($oldname=="")
	{///˵���ǵ�һ�汾���ļ���ֱ������
			clearstatcache();
			download($FolderPath,$FileName);
			
			if(!is_file("/var/log/csc/55.txt.txt"))
			{
			$fp=fopen("/var/log/csc/55.txt","w");
			fclose($fp);
			}
			
			$fp=fopen("/var/log/csc/55.txt","a");
			fwrite($fp,"haha\n");
			fclose($fp);
			updatevisittime($FolderPath);
	}
else
	{//˵���������汾���ļ�������Ҫ�ȸ��µ��°汾
	
			$fp=fopen("/var/log/csc/55.txt","a");
			//����Ӧ�ûָ��ļ�
			$path=dirname($FolderPath);
						
			$FolderPath=$path."/".$oldname;
			$oldname=$FolderPath;
			$newname=$path."/".$newname;
			
			fwrite($fp,"Fielname:".$FileName);
			//�ָ��ļ�
			$commond="/usr/bin/sudo /usr/bin/patch ".$oldname." ".$newname;//.".patch";
			exec($commond,$l);//�ѵ�һ�汾���ļ�ͬ�����°汾������
			fwrite($fp,"command:".$commond."\n");
			fclose($fp);
			//�������ļ�
			//$fp=fopen("/var/log/csc/55.txt","a");
			$file=$oldname.".rej";
	    	$cmd="/usr/bin/sudo /bin/rm ".$oldname.".rej";
	      	if(is_file($file))
			{system($cmd);}
			//fwrite($fp,$cmd."\n");
					
			download($FolderPath,$FileName);
			//�ָ���ԭ�汾
			$commond="/usr/bin/sudo /usr/bin/patch -R ".$oldname." ".$newname;//.".patch";
			exec($commond,$ls);
			
			//fwrite($fp,$cmd);
			//fwrite($fp,"command2:".$commond."\n");
			//fclose($fp);
			
			updatevisittime($FolderPath);
		
	}	

?>