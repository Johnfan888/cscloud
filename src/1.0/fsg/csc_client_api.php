<?php
/*
 * RESTFUL APIs for client 
 * author: zfan
 */
//sleep(2);
$method=$argv[1];
$filename=$argv[2];
$user=$argv[3];
$ms_ip=$argv[4];
//$method=Put;
//$filename="/data/testData/testfile.txt";
//$user="zfan@istl.chd.edu.cn";
//$ms_ip="192.168.1.110";

//Curl POST Call
function curlPost($url,$data,$isJSON=true,$timeout=300)
{
        $ch = curl_init();#初始化
        $curl_opts[CURLOPT_URL] = $url;
        $curl_opts[CURLOPT_HEADER] = false;#获取响应的头信息
        $curl_opts[CURLOPT_RETURNTRANSFER] = true;#获取的信息以文件流的形式返回，而不是直接输出。
        $curl_opts[CURLOPT_POST] = true;
        $curl_opts[CURLOPT_POSTFIELDS] = $data;
        $curl_opts[CURLOPT_TIMEOUT] = $timeout;
        $curl_opts[CURLOPT_VERBOSE] = false;#是否报告每一个意外信息
        curl_setopt_array($ch, $curl_opts);
        $result = curl_exec($ch);
        curl_close($ch);
        if($isJSON)
        {
                $json = json_decode($result, true);
                return $json;
        }
        else
        {
                return $result;
        }
}

//download file from file server
function downloadFile($url, $file="", $data="", $timeout=300)
{
	
	$file = empty($file) ? pathinfo($url,PATHINFO_BASENAME) : $file;
	$dir = pathinfo($file,PATHINFO_DIRNAME);
	// // echo $file;#此处的文件名是编码后的

	$file_url=$file;#114行需要返回一个编码的
    $file=$data['filename'];#获取到的是正常的



	!is_dir($dir) && @mkdir($dir,0755,true);
	$url = str_replace(" ","%20",$url);
	
	set_time_limit(0);
	ini_set('memory_limit', '512M');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	if(!empty($data)) //POST
	{
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	}
		
	/*
	//FIXME Support big file
	$fp = fopen('$file', 'w');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	$info = curl_getinfo($ch);
	$filesize = filesize("$file");
	$curlsize = $info['size_download'];
	curl_close($ch);
	echo "file size:$filesize  curl size:$curlsize\n";	
	if($filesize != $curlsize)
	{
		echo "not integrated. Please download again";
		fclose($fp);
		return false;
	}
	else
	{
		fclose($fp);
		return $file;
	}
	*/

	$output = curl_exec($ch);
	if(!curl_error($ch))
	{
		$info = curl_getinfo($ch);
		//FIXME Why in some case data from curl add one more byte?
		$curlsize = $info['size_download'];
		$fp = fopen($file, 'w');
		$writtenLen = fwrite($fp, $output, $curlsize);
		//$writtenLen = fwrite($fp, $output, $curlsize - 1);
		fclose($fp);
		$filesize = filesize($file);
		curl_close($ch);
		echo "File $file download writtenLen:$writtenLen  file size:$filesize\n";
		//if($filesize != ($curlsize - 1))
		if($filesize != $curlsize)
{
			echo "not integrated. Please download again.\n";
			return false;
		}
		else
		{
			return $file_url;
			// return $file;
		}
	}
	else
	{
		curl_close($ch);
		return false;
	}
}
	
if($method == "Put")
{
	$url="http://".$ms_ip."/manage/csc_manage_http_put.php?method=Put&user=".$user."&filename=".$filename;
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result=curl_exec($ch);
	curl_close($ch);
	$json=json_decode($result, true);
	$fileserverip=$json['fileserverip'];
	$url = "http://{$fileserverip}/www/csc_fileserver_http_put.php";
	$data = array(
		"method" => "Put",
		"status" => $json['status'],
		"userid" => $json['userid'],
		"fileserverpath" => $json['fileserverpath'],
		"filename" => $json['filename'],
		"file_id" => $json['file_id'],
		"version" => $json['version'],
		"replicaip" => $json['replicaip'],
		"replicapath" => $json['replicapath'],
		"managerserverip" => $json['managerserverip'],
		"key" => $json['key'],
		"file" => new CURLFile("{$json['filename']}"),    //For php version 5.5.16 or above
		//'file' => "@{$json['filename']}",          // For php version 5.2.6 (default in sles11sp1)
		"totalsize" => $json['totalsize'],
		"usedsize" => $json['usedsize']);
	$json = curlPost($url,$data,true);
	//echo $json;
	if($json['result'])
	{
		echo "File {$json['filename']} with ID {$json['file_id']} PUT Succeed!\n";
	}
	else
	{
		echo "File {$json['filename']} with ID {$json['file_id']} PUT Failed!\n";
	}
}

if($method == "Get")
{
	$url="http://".$ms_ip."/manage/csc_manage_http_get.php?method=Get&user=".$user."&filename=".$filename;
	// echo $url;
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result=curl_exec($ch);
	curl_close($ch);
	$json=json_decode($result, true);
	if($json['status'] != "found")
	{
		echo "User $user file $filename GET Failed! status:{$json['status']}\n";
		exit;
	}
	// echo $json['filename'];
	
	$fileserverip=$json['fileserverip'];
	$urlPOST = "http://{$fileserverip}/www/csc_fileserver_http_get.php";
	$data = array(
		"method" => "Get",
		"status" => $json['status'],
		"userid" => $json['userid'],
		"fileserverpath" => $json['fileserverpath'],
		"filename" => $json['filename'],
		"file_id" => $json['file_id'],
		"version" => $json['version'],
		"managerserverip" => $json['managerserverip'],
		"key" => $json['key']);
	// print_r($data);
	if(downloadFile($urlPOST, $filename, $data) == $filename)
	{
		echo "File {$json['filename']} with ID {$json['file_id']} GET Succeed!\n";
	}	
	else
	{
		echo "File {$json['filename']} with ID {$json['file_id']} GET Failed!\n";
	}
	
	/*
	$urlGET = "http://{$fileserverip}/www/csc_fileserver_http_get.php?filename={$json['filename']}&file_id={$json['file_id']}&key={$json['key']}&managerserverip={$json['managerserverip']}&fileserverpath={$json['fileserverpath']}&userid={$json['userid']}";
	if(downloadFile($urlGET, $filename) == $filename)
	{
		//echo "File {$json['filename']} with ID {$json['file_id']} GET Succeed!\n";
	}
	else
	{
		//echo "File {$json['filename']} with ID {$json['file_id']} GET Failed!\n";
	}
	*/
}

if($method == "Delete")
{
	$url="http://".$ms_ip."/manage/csc_manage_http_delete.php?method=Delete&user=".$user."&filename=".$filename;
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result=curl_exec($ch);
	curl_close($ch);
	$json=json_decode($result, true);
	
	if($json['status'] != "found")
	{
		echo "User $user file $filename DELETE Failed! status:{$json['status']}\n";
		exit;
	}
        
	$fileserverip=$json['fileserverip'];
	$url = "http://{$fileserverip}/www/csc_fileserver_http_delete.php";
	$data = array(
		"method" => "Delete",
		"userid" => $json['userid'],
		"fileserverpath" => $json['fileserverpath'],
		"replicaip" => $json['replicaip'],
		"replicapath" => $json['replicapath'],
		"filename" => $json['filename'],
		"file_id" => $json['file_id'],
		"version" => $json['version'],
		"managerserverip" => $json['managerserverip'],
		"key" => $json['key'],
		"isReplicated" => $json['isReplicated']);
	$json = curlPost($url,$data,true);
	if($json['result'])
	{
		echo "File {$json['filename']} with ID {$json['file_id']} DELETE Succeed!\n";
	}
	else
	{
		echo "File {$json['filename']} with ID {$json['file_id']} DELETE Failed!\n";
	}
}


if($method == "Post")
{
    $filename_new=$argv[5];
	$url="http://".$ms_ip."/manage/csc_manage_http_post.php?method=Post&user=".$user."&filename=".$filename."&filename_new=".$filename_new;
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result=curl_exec($ch);
	curl_close($ch);
	$json=json_decode($result, true);
	if($json['update_status'] == 1)
	{
		echo "File {$json['filename']} with ID {$json['file_id']} renamed as {$json['filename_new']} Post Succeed!\n";
	}
	else
	{
		echo "File {$json['filename']} with ID {$json['file_id']} renamed as {$json['filename_new']} Post Failed!\n";
	}
}





?>
