<?php 
require("conn.php");
$num=$_POST["num"];
$arr_id=array();
$arr_serverip=array();
$arr_locationpath=array();
$arr_aplicaip=array();
$arr_aplicapath=array();

$user=$_POST["user"];
//将接收到的数组进行整理，整理成各个文件的信息。**********************************************************
//文件的id信息
for($i=0;$i<$num;$i++)
{
	$name="fileid".$i;
	$arr_id[$i]=$_POST[$name];
}
//文件的serverip信息
for($i=0;$i<$num;$i++)
{
	$name="fileserverip".$i;
	$arr_serverip[$i]=$_POST[$name];
}
//文件的locationpath信息
for($i=0;$i<$num;$i++)
{
	$name="filespath".$i;
	$arr_locationpath[$i]=$_POST[$name];
}
//文件的replicaip信息
for($i=0;$i<$num;$i++)
{
	$name="replicaip".$i;
	$arr_aplicaip[$i]=$_POST[$name];
}
//文件的replicapath信息

for($i=0;$i<$num;$i++)
{
	$name="replicapath".$i;
	$arr_aplicapath[$i]=$_POST[$name];
}

//把文件的信息写入post.txt文件，调试时用
$fp=fopen("/var/log/csc/post.txt","w");
for($i=0;$i<$num;$i++)
{
	fwrite($fp,$arr_id[$i]."\n");
	fwrite($fp,$arr_serverip[$i]."\n");
	fwrite($fp,$arr_locationpath[$i]."\n");
	fwrite($fp,$arr_aplicaip[$i]."\n");
	fwrite($fp,$arr_aplicapath[$i]."\n");
}
fclose($fp);

//备份文件
$ch = curl_init();
for($i=0;$i<$num;$i++)
{
		$post_data= array( 
		//"check" => $arr[0],
		"user"=>$user,
		"path"=>$arr_aplicapath[$i],
		"upload" => "@".$arr_locationpath[$i]);
		
		 $url = "http://".$arr_aplicaip[$i]."/www/replicateserver.php" ;
		$output=sendfile($ch,$url,$post_data);
		//调试用得，输出server端的返回信息
		/*$fp=fopen("replicastatus.txt","a");
		fwrite($fp,$output);
		fclose($fp);*/
		
		if ($output===FALSE) 
		{//出错处理
		$fp=fopen("/var/log/csc/backup_error.txt","w");
		    fwrite($fp, "fileid is :".$arr_id[$i]."\n");
		   	 fwrite($fp, "cURL Error: " . curl_error($ch)."\n");
			 fwrite($fp, "cURL Error No.: " .curl_errno($ch)."\n");
		}
		if($output=='1')
		{
		//返回1，说明文件传输成功，则去修该ManageServer上的数据库信息
			$post_data1= array("id"=>$arr_id[$i]);
		   	 $url1 = "http://".$webserverip."/manage/replicaflagmodify.php" ;
			 send($url1,$post_data1);//返回ManageServer修改数据库信息（将flag置成1）
		}
}
curl_close($ch);

/*********************************************************************
备份文件的函数
$ch是curl会话的句柄，$url备份目的服务器端的接收文件，$post_data文件数据
*********************************************************************/
function sendfile($ch,$url,$post_data)
{
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$output=curl_exec($ch);
	return $output;
}	


/*********************************************************************
请求修改ManageServer数据库的函数
$url1备份目的服务器端的接收文件，$post_data1文件数据
*********************************************************************/
function send($url1,$post_data1)
{
	$ch1 = curl_init();
			 curl_setopt($ch1, CURLOPT_URL, $url1);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch1, CURLOPT_POST, 1);
			
			curl_setopt($ch1,CURLOPT_VERBOSE,1);
			curl_setopt($ch1, CURLOPT_TIMEOUT, 30); 
			curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_data1);
			$output1 = curl_exec($ch1);
		    curl_close($ch1);
}	




?>