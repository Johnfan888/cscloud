<?php 

$SCRIPTS_DIR="/cscloud_install_source";
$SRCFILE_PATH="/cscloud_install_source/cscloud/src/1.0";
$DSTFILE_PATH="/srv/www/htdocs";
$DESTPASS="111111";
$ZSIP=$_SERVER['SERVER_ADDR'];
$exec_script="manageserver_setup.php";
require("conn/conn.php");
$SRCFILE=$SRCFILE_PATH."/configmanager/server.txt";//���fs��Ϣ
$DSTFILE=$DSTFILE_PATH."/install/server.txt";

//��ȡmanagerserver����Ϣ
$sql="select * from ip_table where status='manager'";
$result=mysql_query($sql,$conne->getconnect());
$serverinfo=mysql_fetch_array($result);
$DESTIP=$serverinfo["ip_address"]; //ms ip
$SRCZSIP=$SRCFILE_PATH."/configmanager/zsip.txt";
$DSTZSIP=$DSTFILE_PATH."/zsip.txt";
file_put_contents($SRCZSIP,'zabbixserverip:'.$ZSIP);

//��ȡ����fileserver��IP����д��server.txt�У�һ������
$sql="select * from ip_table where status='file'";
$result=mysql_query($sql,$conne->getconnect());
$ips = array();
while($row=mysql_fetch_assoc($result))
{
	$ips[] = $row['ip_address'];
}
$fp = fopen($SRCFILE, 'w');
fwrite($fp, json_encode($ips));
fclose($fp);

//�����õİ�
$command="/bin/sh ".$SCRIPTS_DIR."/csc_mfs_tar_install.sh ".$DESTIP." ".$DESTPASS." ms ".$ZSIP;
exec($command,$output1,$res1); //return value: 0/1/2/3

if($res1==0)//˵������ɹ�
{
	//�����ļ�
	$command="/bin/sh ".$SCRIPTS_DIR."/csc_mfs_file_install.sh ".$SRCFILE." ".$DESTIP." ".$DESTPASS." $DSTFILE";
	exec($command,$output2,$res2);
		
	if($res2==0)
	{////ִ�нű��ļ�
		$command="/bin/sh ".$SCRIPTS_DIR."/csc_mfs_exec_script.sh ".$DESTIP." ".$DESTPASS." ms ".$exec_script;
		exec($command,$output3,$res3);
		echo "������ļ��ķ���ֵΪ��".$res1."<br>���䵥���ļ��ķ���ֵΪ��".$res2."<br>ִ�нű��ķ���ֵΪ".$res3;
		
		if($res3==0)	
		{
			$command="/bin/sh ".$SCRIPTS_DIR."/csc_mfs_file_install.sh ".$SRCZSIP." ".$DESTIP." ".$DESTPASS." $DSTZSIP";
			exec($command,$output4,$res4);
		if($res4 == 0){
			echo "successfully!";
			echo "<a href='installserver.php'>�뷵��</a>";
			}
			else{
			echo "ִ�нű�����";
			echo"res3:".$res4;
			echo "<a href='installserver.php'>�뷵��</a>";	
			}
		}
		else
		{
			echo "ִ�нű�����";
			echo"res3:".$res3;
			echo "<a href='installserver.php'>�뷵��</a>";
		}
	}
	else
	{
		echo "�����ļ�����";
		echo"res3:".$res3;
		echo "<a href='installserver.php'>�뷵��</a>";
	}
}		
else
{
	echo "�����õİ�����";
	echo "res1:".$res1;
	echo "<a href='installserver.php'>�뷵��</a>";
//	fclose($fp);
}

 //return value: 0/1




?>
