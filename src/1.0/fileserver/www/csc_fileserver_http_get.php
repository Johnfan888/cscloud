<?php 
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
$method = $_GET['method']; //Get
$owner = $_GET['owner']; //uid
$dirpath = $_GET['dirpath']; //base-path
$filename = $_GET['filename'];//uuid
$dirpath=$dirpath.$owner."/";

	//S3 full path
	/*$fullPath = $dirpath.$filename;
	//swift full path
	$arr=substr($filename,-3);
	$dirpath=$dirpath.$arr."/";
	$fullPath=$dirpath.$filename;*/
	 //FDDM (рт╨С)
	$arr1=substr($filename,0,1);
	$arr1=md5($arr1);
	$arr2=substr($filename,1,1);
	$arr2=md5($arr2);
	$dirpath=$dirpath.$arr1."/".$arr2."/";
	$fullPath=$dirpath.$filename;
	$fh=fopen('/var/log/csc/test2','w+');
	fwrite($fh,$fullPath);
	fclose($fh);
	ob_end_clean();	// clean buffering data quietly
	echo readfile($fullPath);
	//echo "hello1";
?>