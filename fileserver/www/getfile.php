<?php
function download($FolderPath, $FileName)
{
	 header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			//header("Content-Type: application/download");
			// 以真实文件名提供给浏览器下载.
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
//通知manager更新该文件的visittime信息，并将该文件信息放入tb_file_cache
		require("configure_class.php");
		$c = new Configuration();
		$c->_construct();
		$webserverip=trim($c->_get("ManagerServerIP"));
		$visittime=date("Y-m-d H:i:s", fileatime($FolderPath));//获取文件的访问时间
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
		  $output=Postarray($url,$post_data);//发送一个表单的函数

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
	{///说明是第一版本的文件，直接下载
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
	{//说明是其他版本的文件，则需要先更新到新版本
	
			$fp=fopen("/var/log/csc/55.txt","a");
			//下面应该恢复文件
			$path=dirname($FolderPath);
						
			$FolderPath=$path."/".$oldname;
			$oldname=$FolderPath;
			$newname=$path."/".$newname;
			
			fwrite($fp,"Fielname:".$FileName);
			//恢复文件
			$commond="/usr/bin/sudo /usr/bin/patch ".$oldname." ".$newname;//.".patch";
			exec($commond,$l);//把第一版本的文件同步成新版本的内容
			fwrite($fp,"command:".$commond."\n");
			fclose($fp);
			//再下载文件
			//$fp=fopen("/var/log/csc/55.txt","a");
			$file=$oldname.".rej";
	    	$cmd="/usr/bin/sudo /bin/rm ".$oldname.".rej";
	      	if(is_file($file))
			{system($cmd);}
			//fwrite($fp,$cmd."\n");
					
			download($FolderPath,$FileName);
			//恢复成原版本
			$commond="/usr/bin/sudo /usr/bin/patch -R ".$oldname." ".$newname;//.".patch";
			exec($commond,$ls);
			
			//fwrite($fp,$cmd);
			//fwrite($fp,"command2:".$commond."\n");
			//fclose($fp);
			
			updatevisittime($FolderPath);
		
	}	

?>