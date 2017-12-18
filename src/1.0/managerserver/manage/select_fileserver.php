<?php
//新建目录时选择服务器,并转到相应的脚本
function selectserver($iparray,$filepath,$servernum,$user,$dirpath,$dirname,$parent_id)
{
		$result=selectfileserver($iparray,$filepath,$servernum);
		$serverip=$result[0];
		$userfilepath=$result[1];
		$serverip1=$result[2];
		$repath=$result[3];
		header("location:http://".$serverip."/www/index.php?dir=".$dirname."&username=".$user."&flag=newdir"."&parent_id=".$parent_id."&dirpath=".$dirpath."&replicaip=".$serverip1."&replicapath=".$repath."&userfilepath=".$userfilepath."&manageserverip=".$_SERVER['SERVER_ADDR']);
	
}


//备份文件时选择文件服务器，并转到相应脚本serverip是用来存储用户备份的文件的，$serverip是用来对用户文件进行备份的
function selectserver1($iparray,$filepath,$servernum,$user,$dirpath,$totalsize,$usedsize)
{
	$result=selectfileserver($iparray,$filepath,$servernum);
	$serverip=$result[0];
	$userfilepath=$result[1];
	$serverip1=$result[2];
	$repath=$result[3];
	header("location:http://".$serverip."/www/index.php?username=".$user."&flag=upload"."&parent_id=".$_GET["parent_id"]."&dirpath=".$dirpath."&replicaip=".$serverip1."&replicapath=".$repath."&userfilepath=".$userfilepath."&totalsize=".$totalsize."&usedsize=".$usedsize."&manageserverip=".$_SERVER['SERVER_ADDR']);
	
	
}
//C客户端备份文件选择文件服务器，并转到相应脚本
function selectserver2($iparray,$filepath,$servernum,$user,$totalsize,$usedsize)
{
	$result=selectfileserver($iparray,$filepath,$servernum);
	$serverip=$result[0];
	$userfilepath=$result[1];
	$serverip1=$result[2];
	$repath=$result[3].$user.'/';
	echo $serverip."&".$serverip1."&".$repath."&".$totalsize."&".$usedsize."&".$userfilepath."&".$_SERVER['SERVER_ADDR'];
}
//用于备份文件时选择服务器
function selectfileserver($iparray,$filepath,$servernum)
{	
	$fp=fopen("fileserver.txt","r");
	$a=fread($fp,filesize("fileserver.txt"));
	fclose($fp);

	for($j=0;$j<$servernum;$j++)
	{
		if(($a%$servernum)==$j)
		{
		$serverip=$iparray[$j];//选择存储服务器
		$userfilepath=$filepath[$j];
		  if(($j+1)==$servernum)//选择备份服务器,选择原则就是ip数组中j后面的一个
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