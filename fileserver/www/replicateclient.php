<?php 
require("conn.php");
$num=$_POST["num"];
$arr_id=array();
$arr_serverip=array();
$arr_locationpath=array();
$arr_aplicaip=array();
$arr_aplicapath=array();

$user=$_POST["user"];
//�����յ������������������ɸ����ļ�����Ϣ��**********************************************************
//�ļ���id��Ϣ
for($i=0;$i<$num;$i++)
{
	$name="fileid".$i;
	$arr_id[$i]=$_POST[$name];
}
//�ļ���serverip��Ϣ
for($i=0;$i<$num;$i++)
{
	$name="fileserverip".$i;
	$arr_serverip[$i]=$_POST[$name];
}
//�ļ���locationpath��Ϣ
for($i=0;$i<$num;$i++)
{
	$name="filespath".$i;
	$arr_locationpath[$i]=$_POST[$name];
}
//�ļ���replicaip��Ϣ
for($i=0;$i<$num;$i++)
{
	$name="replicaip".$i;
	$arr_aplicaip[$i]=$_POST[$name];
}
//�ļ���replicapath��Ϣ

for($i=0;$i<$num;$i++)
{
	$name="replicapath".$i;
	$arr_aplicapath[$i]=$_POST[$name];
}

//���ļ�����Ϣд��post.txt�ļ�������ʱ��
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

//�����ļ�
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
		//�����õã����server�˵ķ�����Ϣ
		/*$fp=fopen("replicastatus.txt","a");
		fwrite($fp,$output);
		fclose($fp);*/
		
		if ($output===FALSE) 
		{//������
		$fp=fopen("/var/log/csc/backup_error.txt","w");
		    fwrite($fp, "fileid is :".$arr_id[$i]."\n");
		   	 fwrite($fp, "cURL Error: " . curl_error($ch)."\n");
			 fwrite($fp, "cURL Error No.: " .curl_errno($ch)."\n");
		}
		if($output=='1')
		{
		//����1��˵���ļ�����ɹ�����ȥ�޸�ManageServer�ϵ����ݿ���Ϣ
			$post_data1= array("id"=>$arr_id[$i]);
		   	 $url1 = "http://".$webserverip."/manage/replicaflagmodify.php" ;
			 send($url1,$post_data1);//����ManageServer�޸����ݿ���Ϣ����flag�ó�1��
		}
}
curl_close($ch);

/*********************************************************************
�����ļ��ĺ���
$ch��curl�Ự�ľ����$url����Ŀ�ķ������˵Ľ����ļ���$post_data�ļ�����
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
�����޸�ManageServer���ݿ�ĺ���
$url1����Ŀ�ķ������˵Ľ����ļ���$post_data1�ļ�����
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