#/usr/bin/php
<?php
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();

$res=selectreplicatefile($user->Con1);//����ѡ�񱸷��ļ��Ĳ���
$groupnum=mysql_num_rows($res);//���㹲�м��鱸��Դ


$ch = curl_init();//��ʼ��һ��curl�Ի�������һ�� CURL handle 
for($j=0;$j<$groupnum;$j++)//�Ը�����Դ���ļ���Ϣ������������$postarray���飬���͸�������Դ
{
        $menunu=mysql_fetch_array($res);
//$menunu������0��ʾid��1��ʾserverip��2��ʾlocationpath��3��ʾreplicaip��4��ʾreplicalocation��5��ʾflag			
		$arr_id=array();
		$arr_serverip=array();
		$arr_locationpath=array();
		$arr_aplicaip=array();
		$arr_aplicapath=array();
		$postarray=array();
		//����ѡ�񱸷��ļ�
		$postarray["user"]="echo";
		$sql="select *  from tb_file_location where flag='0' and serverip='".$menunu["serverip"]."'";
		$result=mysql_query($sql,$user->Con1);
		$num=mysql_num_rows($result);
	//	echo "num is:".$num;
		
		//����Ӧ���ļ���Ϣ���д����$postarray***********************************
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
		//��postarray�а���id��Ϣ
		for($i=0;$i<$num;$i++)
		{
			$name="fileid".$i;
			$postarray[$name]=$arr_id[$i];
		}
		//��postarray�а���ip��Ϣ
		for($i=0;$i<$num;$i++)
		{
			$name="fileserverip".$i;
			$postarray[$name]=$arr_serverip[$i];
		}
		//��postarray�а���locationpath��Ϣ
		for($i=0;$i<$num;$i++)
		{
			$name="filespath".$i;
			$postarray[$name]=$arr_locationpath[$i];
		}
		//��postarray�а���replicaip��Ϣ
		for($i=0;$i<$num;$i++)
		{
			$name="replicaip".$i;
			$postarray[$name]=$arr_aplicaip[$i];
		}
		//��postarray�а���replicapath��Ϣ
		for($i=0;$i<$num;$i++)
		{
			$name="replicapath".$i;
			$postarray[$name]=$arr_aplicapath[$i];
		}
		//print_r($postarray);//�����õ�
		//�����ɣ����淢��**********************************************
		
		
		//����$url,��$postarray���͸�����Դ
		$url="http://".$menunu['serverip']."/www/replicateclient.php";
		send($ch,$url,$postarray);	
				
}
curl_close($ch);//�ر�curl�Ի�

				
/************************************************************************
*************************************************************************
����ѡ�����ݿ�����Ҫ���ݵ��ļ�������ѡ��ļ�¼
$Con1�����ݿ����ӵĲ���
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
ʹ��һ��curl�Ի���������Դ����Ϣ���͸���Ӧ�ı���Դ,�������ݲ������صĽ��
д��replicatefile.txt�ļ���
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
	//��������Ϣд���ļ�
	$fp=fopen("log/replicate_error.txt","a");
	fwrite($fp,$output);
	fclose($fp);
				
}			
				
?>