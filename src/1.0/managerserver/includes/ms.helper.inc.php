<?php
/*
 * Help functions for ms
 * author: zfan
 */

date_default_timezone_set("PRC");

define("ID_LAYER", "4");	//or 2

//记录日志
function WriteLog($logDirName, $msg)
{
	 $basepath =  '/var/log/csc';
	 if(!file_exists($basepath))
		mkdir($basepath);

	 $logpath = $basepath . "/{$logDirName}/";
	 if(!file_exists($logpath))
		 mkdir($logpath);

	 $fp = fopen($logpath . date('Y-m-d') . '.txt', 'ab');
	 fwrite($fp, 'time:' . date('Y-m-d H:i:s'));
	 fwrite($fp, "\n");
	 fwrite($fp, "<message>  {$msg}");
	 fwrite($fp, "\n");
	 fwrite($fp, "\n");
	 fclose($fp);
}

//生成唯一的文件名
function UniqueName()
{ 
	$token = md5(uniqid(rand()));
	return $token;
}

//UDPM (Uniform Data Placement Method)
function udpm_dir($file_id, $len)
{
	$arr = str_split($file_id, 8);
	$path = "";
	for($layer = 0; $layer < ID_LAYER; $layer++)
	{
		$segPath = md5(substr($arr[$layer], -$len));
		$path = $path.$segPath."/";
	}
}

function udpm_rmdir($fullPath, $file_id)
{
	$fullPathLen = strlen($fullPath);
	$segLen = strlen($file_id);
	for($layer = 0; $layer < ID_LAYER; $layer++)
	{
		rmdir(substr($fullPath, 0, $fullPathLen - $segLen -1));
		$fullPathLen = $fullPathLen - $segLen -1;
	}
}

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
?>

