<?php
/*
 * RESTFUL APIs for client 
 * author: zfan
 */

$method=$argv[1];
$filename="/data/testData/testfile.txt";
$user="zfan@istl.chd.edu.cn";
//$user="liang@istl.pcd.com";
$ms_ip="192.168.1.116";

//Curl POST Call
function curlPost($url,$data,$isJSON=true,$timeout=60)
{
        $ch = curl_init();
        $curl_opts[CURLOPT_URL] = $url;
        $curl_opts[CURLOPT_HEADER] = false;
        $curl_opts[CURLOPT_RETURNTRANSFER] = true;
        $curl_opts[CURLOPT_POST] = true;
        $curl_opts[CURLOPT_POSTFIELDS] = $data;
        $curl_opts[CURLOPT_TIMEOUT] = $timeout;
        $curl_opts[CURLOPT_VERBOSE] = false;
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
function downloadFile($url, $file="", $data="", $timeout=60)
{
	$file = empty($file) ? pathinfo($url,PATHINFO_BASENAME) : $file;
	$dir = pathinfo($file,PATHINFO_DIRNAME);
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
			return $file;
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
		"file" => new CURLFile("{$json['filename']}"),	//For php version 5.5.16 or above
		//'file' => "@{$json['filename']}"	// For php version 5.2.6 (default in sles11sp1)
		"totalsize" => $json['totalsize'],
		"usedsize" => $json['usedsize']);
	$json = curlPost($url,$data,true);
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
		"key" => $json['key']);
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
?>

