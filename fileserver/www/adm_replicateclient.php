<?php 
require("conn.php");
$num=$_POST["num"];

$fp = fopen("/var/log/csc/replicate_client","a+");
$ch = curl_init();
$sucs_num = 0;
$fail_num = 0;
for($i = 0; $i < $num; $i++)
{
	$filename = $_POST["filename".$i];
	$owner = $_POST["owner".$i];
	$serverip = $_POST["serverip".$i];
	$location = $_POST["location".$i];
	$replicaip = $_POST["replicaip".$i];
	$replicalocation = $_POST["replicalocation".$i];
	fwrite($fp, date("Y-m-d H:i:s")." replicating owner:$owner file name:$filename from $serverip:$location to $replicaip:$replicalocation\n");
	
	$post_data = array(
		"filename" => $filename,
		"owner" => $owner,
		"serverip" => $serverip,
		"location" => $location,
		"upload" => "@".$location,
		"replicaip" => $replicaip,
		"replicalocation" => $replicalocation);

	$url = "http://".$replicaip."/www/adm_replicateserver.php";
	$output = send($ch, $url, $post_data);
	if(trim($output) == "SUCS")
	{
		$post_data = array("filename" => $filename);
		$url = "http://".$webserverip."/manage/adm_replicaflagmodify.php";
		send($ch, $url, $post_data);
		$sucs_num++;
	}
	else
	{
		fwrite($fp, "cURL Error:" . curl_error($ch)."\n");
		fwrite($fp, "cURL Error No.:" .curl_errno($ch)."\n");
		$fail_num++;
	}
}
echo "success number:$sucs_num	fail number:$fail_num\n";
curl_close($ch);
fclose($fp);

function send($ch, $url, $post_data)
{
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	//curl_setopt($ch1,CURLOPT_VERBOSE,1);
	//curl_setopt($ch1, CURLOPT_TIMEOUT, 30); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$output = curl_exec($ch);
	return $output;
}	
?>
