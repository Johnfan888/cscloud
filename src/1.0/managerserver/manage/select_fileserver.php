<?php
//�½�Ŀ¼ʱѡ�������,��ת����Ӧ�Ľű�
function selectserver($iparray,$filepath,$servernum,$user,$dirpath,$dirname,$parent_id)
{
		$result=selectfileserver($iparray,$filepath,$servernum);
		$serverip=$result[0];
		$userfilepath=$result[1];
		$serverip1=$result[2];
		$repath=$result[3];
		header("location:http://".$serverip."/www/index.php?dir=".$dirname."&username=".$user."&flag=newdir"."&parent_id=".$parent_id."&dirpath=".$dirpath."&replicaip=".$serverip1."&replicapath=".$repath."&userfilepath=".$userfilepath."&manageserverip=".$_SERVER['SERVER_ADDR']);
	
}


//�����ļ�ʱѡ���ļ�����������ת����Ӧ�ű�serverip�������洢�û����ݵ��ļ��ģ�$serverip���������û��ļ����б��ݵ�
function selectserver1($iparray,$filepath,$servernum,$user,$dirpath,$totalsize,$usedsize)
{
	$result=selectfileserver($iparray,$filepath,$servernum);
	$serverip=$result[0];
	$userfilepath=$result[1];
	$serverip1=$result[2];
	$repath=$result[3];
	header("location:http://".$serverip."/www/index.php?username=".$user."&flag=upload"."&parent_id=".$_GET["parent_id"]."&dirpath=".$dirpath."&replicaip=".$serverip1."&replicapath=".$repath."&userfilepath=".$userfilepath."&totalsize=".$totalsize."&usedsize=".$usedsize."&manageserverip=".$_SERVER['SERVER_ADDR']);
	
	
}
//C�ͻ��˱����ļ�ѡ���ļ�����������ת����Ӧ�ű�
function selectserver2($iparray,$filepath,$servernum,$user,$totalsize,$usedsize)
{
	$result=selectfileserver($iparray,$filepath,$servernum);
	$serverip=$result[0];
	$userfilepath=$result[1];
	$serverip1=$result[2];
	$repath=$result[3].$user.'/';
	echo $serverip."&".$serverip1."&".$repath."&".$totalsize."&".$usedsize."&".$userfilepath."&".$_SERVER['SERVER_ADDR'];
}
//���ڱ����ļ�ʱѡ�������
function selectfileserver($iparray,$filepath,$servernum)
{	
	$fp=fopen("fileserver.txt","r");
	$a=fread($fp,filesize("fileserver.txt"));
	fclose($fp);

	for($j=0;$j<$servernum;$j++)
	{
		if(($a%$servernum)==$j)
		{
		$serverip=$iparray[$j];//ѡ��洢������
		$userfilepath=$filepath[$j];
		  if(($j+1)==$servernum)//ѡ�񱸷ݷ�����,ѡ��ԭ�����ip������j�����һ��
		   {
			  $serverip1=$iparray[0];
			  $repath=$filepath[0];
		   }
		   else
		   {
				$serverip1=$iparray[$j+1];
			   $repath=$filepath[$j+1];
		   }
		}
	}
	$a=$a+1;
	if($a>1000)
	{
		$a=0;
	}
	$fp=fopen("fileserver.txt","w");
	fwrite($fp,$a);
	fclose($fp);

	$result=array();
	$result[]=$serverip;
	$result[]=$userfilepath;
	$result[]=$serverip1;
	$result[]=$repath;
	return $result;

}


?>