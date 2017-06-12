#/usr/bin/php
<?php
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();

$res=selectreplicatefile($user->Con1);//调用选择备份文件的参数
$groupnum=mysql_num_rows($res);//计算共有几组备份源


$ch = curl_init();//初始化一个curl对话，返回一个 CURL handle 
for($j=0;$j<$groupnum;$j++)//对各备份源的文件信息进行整理打包成$postarray数组，发送给各备份源
{
        $menunu=mysql_fetch_array($res);
//$menunu数组中0表示id，1表示serverip，2表示locationpath，3表示replicaip，4表示replicalocation，5表示flag			
		$arr_id=array();
		$arr_serverip=array();
		$arr_locationpath=array();
		$arr_aplicaip=array();
		$arr_aplicapath=array();
		$postarray=array();
		//按组选择备份文件
		$postarray["user"]="echo";
		$sql="select *  from tb_file_location where flag='0' and serverip='".$menunu["serverip"]."'";
		$result=mysql_query($sql,$user->Con1);
		$num=mysql_num_rows($result);
	//	echo "num is:".$num;
		
		//对相应的文件信息进行打包成$postarray***********************************
		for($i=0;$i<$num;$i++)
		{
			$postarray["num"]=$num;
			$menu=mysql_fetch_array($result);
			$arr_id[$i]=$menu['id'];
			$arr_serverip[$i]=$menu['serverip'];
			$arr_locationpath[$i]=$menu['locationpath'];
			$arr_aplicaip[$i]=$menu['replicaip'];
			$arr_aplicapath[$i]=$menu['replicalocation'];
		}
		//让postarray中包含id信息
		for($i=0;$i<$num;$i++)
		{
			$name="fileid".$i;
			$postarray[$name]=$arr_id[$i];
		}
		//让postarray中包含ip信息
		for($i=0;$i<$num;$i++)
		{
			$name="fileserverip".$i;
			$postarray[$name]=$arr_serverip[$i];
		}
		//让postarray中包含locationpath信息
		for($i=0;$i<$num;$i++)
		{
			$name="filespath".$i;
			$postarray[$name]=$arr_locationpath[$i];
		}
		//让postarray中包含replicaip信息
		for($i=0;$i<$num;$i++)
		{
			$name="replicaip".$i;
			$postarray[$name]=$arr_aplicaip[$i];
		}
		//让postarray中包含replicapath信息
		for($i=0;$i<$num;$i++)
		{
			$name="replicapath".$i;
			$postarray[$name]=$arr_aplicapath[$i];
		}
		//print_r($postarray);//调试用得
		//打包完成，下面发送**********************************************
		
		
		//设置$url,将$postarray发送给备份源
		$url="http://".$menunu['serverip']."/www/replicateclient.php";
		send($ch,$url,$postarray);	
				
}
curl_close($ch);//关闭curl对话

				
/************************************************************************
*************************************************************************
用于选择数据库中需要备份的文件，返回选择的记录
$Con1是数据库连接的参数
*************************************************************************
*************************************************************************/				
function selectreplicatefile($Con1)
{

	$sql="select *,count(*) from  tb_file_location where flag='0' group by serverip";
	$res=mysql_query($sql,$Con1);
    return $res;
}			
				
/************************************************************************
*************************************************************************
使用一个curl对话将各备份源的信息发送给相应的备份源,并将备份操作返回的结果
写入replicatefile.txt文件中
*************************************************************************
*************************************************************************/					

function send($ch,$url,$postarray)
{
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postarray);
	$output=curl_exec($ch);
	//将错误信息写入文件
	$fp=fopen("log/replicate_error.txt","a");
	fwrite($fp,$output);
	fclose($fp);
				
}			
				
?>