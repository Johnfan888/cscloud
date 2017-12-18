<?php
function writeFile($filename)
{
	/* Read data from the stdin stream */
	$fp_input = fopen("php://input", "r"); //read post data

	/* Open a file for writing */
	$fp = fopen($filename, "w");
	
	/* Read 8 KB at a time and write to the file */
	while ($data = fread($fp_input, 1024 * 8))
		fwrite($fp, $data);

	/* Close the streams */
	fclose($fp);
	fclose($fp_input);
}

function read_File($filename)
{
	/* Write data to the stdout stream */
	$fp_output = fopen("php://output", "w"); //只写数据流，数据写入到输出缓存中。

	/* Open a file for reading */
	$fp = fopen($filename, "r");
	
	/* Read 8 KB at a time and write to the stdout */
	while ($data = fread($fp, 1024 * 8))
		fwrite($fp_output, $data);

	/* Close the streams */
	fclose($fp);
	fclose($fp_output);
}
 
function send($url,$post_data) //Curl调用
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	//curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_exec($ch);
	curl_close($ch);
}

$method = $_GET['method'];
$owner = $_GET['owner']; //use useid replace owner
$dirpath = $_GET['dirpath']; //存储路径
$filename = $_GET['filename']; //文件名
//ha info
$replicaip=$_GET['replicaip'];
$replicapath=$_GET['replicapath'];
//TODO muti-version info
$version=$_GET['version']; //版本
$dirpath=$dirpath.$owner."/";
/*$fh=fopen('/var/log/csc/test1.txt','w+');
fwrite($fh,'hi');
fclose($fh);*/
/*	//  S3 write file 
	$fullPath = $dirpath.$filename;  //S3完整路径
*/
	//swift
	/*$arr=substr($filename,-3);
	$dirpath=$dirpath.$arr."/";
	$fullPath=$dirpath.$filename;*/
	 //FDDM (以后)
	$arr1=substr($filename,0,1);
	$arr1=md5($arr1);
	$arr2=substr($filename,1,1);
	$arr2=md5($arr2);
	$dirpath=$dirpath.$arr1."/".$arr2."/";
	$fullPath=$dirpath.$filename;
	
	//public
	
	if(!is_dir($dirpath)) {//判断路径
	mkdir($dirpath, 0755, "-p");
	}
	writeFile($fullPath); //上传的数据写入目录
	$size=filesize($fullPath);
	$post_data = array( 
		"filename" => $filename,
		"owner" => $owner,
		"serverip" => $_SERVER['SERVER_ADDR'],
		"location" => $fullPath,
		"replicaip" => $replicaip,
		"replicalocation" => $replicapath,
	    "size"=>$size,
	    "version"=>$version,
	    "dir_path"=>$_GET['dirpath']);
		
	$url = "http://".$_GET['managerserverip']."/manage/csc_http_put_inserttodb.php";
	send($url, $post_data);
	ob_end_clean();	// clean buffering data quietly
	
 ?>   



