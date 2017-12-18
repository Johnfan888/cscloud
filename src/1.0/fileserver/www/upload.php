<?php
require("configure_class.php");
$c = new Configuration();
$c->_construct();
$webserverip=trim($c->_get("ManagerServerIP"));
$serverid=trim($c->_get("ServerID"));
$userPath= trim($c->_get("UserFilePath"));
/*$webserverip=trim($_GET["manageserverip"]);
$serverid=trim($_GET["fileserverid"]);
$userPath= trim($_GET["userfilepath"]);*/
$dirpath=trim($_GET["dirpath"]);//此时的路径是不包括文件服务器上的数据存储路径和用户名的
$dir=$userPath.$_GET['owner'].$dirpath;//此时的路径是文件服务器的绝对存储路径
//判断目录是否存在，如果不存在的话，先建立目录
if(!is_dir($dir))
{
	mkdir($dir,0777);
}

$owner = $_GET['owner'];
$parent_id=$_GET['parent_id'];
$id="";//变量初始化
//获取名称;
$oldName_f=$_GET['oldName'];
$newName_f=$_GET['newName'];


//获取file数组
$f=$_FILES['Filedata'];
//--------------------------------------------------------------------------------------------
function randStr($len=6) { 
$chars='0123456789'; // characters to build the password from 
mt_srand((double)microtime()*1000000*getmypid()); // seed the random number generater (must be done) 
$password=''; 
while(strlen($password)<$len) 
$password.=substr($chars,(mt_rand()%strlen($chars)),1); 
return $password; 
} 
//--------------------------------------------------------------------------------------------

$pos=strrpos($newName_f,"."); //取得文件名中后缀名的开始位置
$ext=substr($newName_f,$pos);//取得后缀名，包括点号

$oldName_f=$oldName_f.$ext;

//设定上传目录



date_default_timezone_set('Asia/Shanghai');
$showtime=date("YmdHis");
$rondstr=randStr(4);
$id=$showtime.$rondstr;

$uploadfile = $dir.$id;
$filename=$oldName_f;
$url=$uploadfile;
      
$filesize=$_FILES['Filedata']['size'];
$totalsize=$_GET["totalsize"];	
$usedsize=countDirSize($userPath.$_GET['owner']);	
$freesize=$totalsize-$usedsize-$filesize;
if($freesize>0)
{

//把临时文件以新的名称保存到指定的目录
/*move_uploaded_file($f['tmp_name'],$dir.'/'.$newName_f);*/
		if(move_uploaded_file($_FILES['Filedata']['tmp_name'],$uploadfile))
		{
				//$owner=$_COOKIE["username"];
				$filesize=filesize("$uploadfile");
				$createtime=date("Y-m-d H:i:s", filectime("$uploadfile"));
				$visittime=date("Y-m-d H:i:s", fileatime("$uploadfile"));
				$modifytime=date("Y-m-d H:i:s", filemtime("$uploadfile")); 
				
				//重新计算用户所剩的磁盘空间					 
				$userPath = $userPath .$_GET['owner'];
				$fp=fopen("user.txt","w");
				fwrite($fp,$userPath);
				fclose($fp);
				$userDirSize=countDirSize($userPath);
				//记录日志---------------------------------------------
				if(!is_file("/var/log/csc/data_log.txt"))
				{
					$fp=fopen("/var/log/csc/data_log.txt","w");
					fclose($fp);
				}
				$fp=fopen("/var/log/csc/data_log.txt","a");
				fwrite($fp,date("Y-m-d H:i:s")."		");
				fwrite($fp,"upload		");
				fwrite($fp,$id."		");
				fwrite($fp,$filename."		");
				fwrite($fp,$_GET['owner']."		");
				fwrite($fp,$_GET['replicaip']."		");
				fwrite($fp,$_GET['replicapath']."		");
				fwrite($fp,$serverid."\n");
				fclose($fp);		
				//--------------------------------------------
				
				
				
				//把上传文件的信息传递给manageserver，插入数据库
				$post_url = "http://".$webserverip."/manage/insertintodb.php" ;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $post_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				$post_data= array( 
				"id" => $id,
				"filename"=>$filename,
				"url" =>$url,
				"filesize" =>$filesize,
				"type" =>"0",
				"createtime" =>$createtime,
				"parent_id" =>$_GET['parent_id'],
				"visittime" =>$visittime,
				"modifytime" =>$modifytime,
				"owner" =>$_GET['owner'],
				"replicaip" =>$_GET['replicaip'],
				"replicapath" =>$_GET['replicapath'].$_GET["owner"].$dirpath.$id,
				"serverid" =>$serverid,
				"userDirSize" =>$userDirSize
				);
				curl_setopt($ch,CURLOPT_VERBOSE,1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				$output = curl_exec($ch);
				if ($output===FALSE) 
				{//出错处理,写入错误日志中
				
					if(!is_file("/var/log/csc/error_log.txt.txt"))
					{
						$fp=fopen("/var/log/csc/error_log.txt","w");
						fclose($fp);
					}
					$fp=fopen("/var/log/csc/error_log.txt","a");
					fwrite($fp,date("Y-m-d H:i:s")."		");
					fwrite($fp,"upload   ");
					fwrite($fp,"cURL Error:".curl_error($ch)."   ");
					fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
					$flag="0";
					// break;
				} 
				curl_close($ch);
				
	 		}
		else{// upload error
			}
}
else{//空间不足，则不上传

}

function countDirSize($dir)
{
$sizeResult=0;
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
?>