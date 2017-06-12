<?php
function send($ch, $url, $postarray)
{
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postarray);
	$output = curl_exec($ch);
	return $output;
}			
				
require("../include/comment.php");
require("../include/user.class.php");

$user = &username::getInstance();

$sql = "select *,count(*) from  adm_file_location where flag='0' group by serverip";
$res1 = mysql_query($sql, $user->Con1);
$groupnum = mysql_num_rows($res1);

$postarray = array();
$ch = curl_init(); 
$fp = fopen("/var/log/csc/replicate_manager", "a+");
for($j = 0; $j < $groupnum; $j++)
{
	$item1 = mysql_fetch_array($res1);
	$serverip = $item1["serverip"]; 
	$sql = "select *  from adm_file_location where flag='0' and serverip='".$serverip."'";
	$res2 = mysql_query($sql, $user->Con1);
	$num = mysql_num_rows($res2);
	fwrite($fp, date("Y-m-d H:i:s")." $num files on $serverip will be replicated ...\n");
	$postarray["num"] = $num;
	for($i = 0; $i < $num; $i++)
	{
		$item2 = mysql_fetch_array($res2);
		$filename = $item2['filename'];
		$postarray["filename".$i] = $filename;
		// TODO add authentication by owner information
		$owner = $item2['owner'];
		$postarray["owner".$i] = $owner;
		$postarray["serverip".$i] = $serverip;
		$location = $item2['location'];
		$postarray["location".$i] = $location;
		$replicaip = $item2['replicaip'];
		$postarray["replicaip".$i] = $replicaip;
		$replicalocation = $item2['replicalocation'];
		$postarray["replicalocation".$i] = $replicalocation;
		fwrite($fp, "owner:$owner file name:$filename from $serverip:$location to $replicaip:$replicalocation\n");
	}
	$url = "http://".$serverip."/www/adm_replicateclient.php";
	$output = send($ch, $url, $postarray);	
	fwrite($fp, "replication completed with result: ".trim($output)."\n");
}
curl_close($ch);
fclose($fp);
?>
