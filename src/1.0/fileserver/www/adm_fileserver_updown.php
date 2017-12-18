<?php
function writeFile($filename)
{
	/* Read data from the stdin stream */
	$fp_input = fopen("php://input", "r");

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
	$fp_output = fopen("php://output", "w");

	/* Open a file for reading */
	$fp = fopen($filename, "r");
	
	/* Read 8 KB at a time and write to the stdout */
	while ($data = fread($fp, 1024 * 8))
		fwrite($fp_output, $data);

	/* Close the streams */
	fclose($fp);
	fclose($fp_output);
}
 
function send($url,$post_data)
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

require("conn.php");
$method = $_GET['method'];
$owner = $_GET['owner'];
$dirpath = $_GET['dirpath'];
$filename = $_GET['filename'];

$fullPath = $dirpath.$filename;
if($method == 'upload')
{
	if(!is_dir($dirpath))
		mkdir($dirpath, 0755, "-p");
	// write file 
	writeFile($fullPath);
		
	$post_data = array( 
		"filename" => $filename,
		"owner" => $owner,
		"serverip" => $_SERVER['SERVER_ADDR'],
		"location" => $fullPath,
		"replicaip" => $_GET['replicaip'],
		"replicalocation" => $_GET['replicapath'].$filename);
		
	$url = "http://".$_GET['managerserverip']."/manage/adm_insertintodb.php";
	send($url, $post_data);
	ob_end_clean();	// clean buffering data quietly

	// setcookie("status", "fail", time() + 3600, "/");
}
else if($method == 'download')
{
	ob_end_clean();	// clean buffering data quietly
	readfile($fullPath);
	//read_File($fullPath);
}
else if($method == 'delete')
{
	unlink($fullPath);
	$post_data = array("filename"=>$filename, "serverip"=>$_SERVER['SERVER_ADDR'], "location"=>$fullPath);
	$url = "http://".$_GET['managerserverip']."/manage/adm_deletefromdb.php";
	send($url, $post_data);
	ob_end_clean();	// clean buffering data quietly
}
else
{
	echo "Not supported method $method\n";
}
?>
