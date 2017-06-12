<?php 

$SCRIPTS_DIR="/cscloud_install_source";
$SRCFILE_PATH="/cscloud_install_source/cscloud/src/1.0";
$DSTFILE_PATH="/srv/www/htdocs";
$DESTPASS="111111";
$ZSIP=$_SERVER['SERVER_ADDR'];
$exec_script="manageserver_setup.php";
require("conn/conn.php");
$SRCFILE=$SRCFILE_PATH."/configmanager/server.txt";//存放fs信息
$DSTFILE=$DSTFILE_PATH."/install/server.txt";

//获取managerserver的信息
$sql="select * from ip_table where status='manager'";
$result=mysql_query($sql,$conne->getconnect());
$serverinfo=mysql_fetch_array($result);
$DESTIP=$serverinfo["ip_address"]; //ms ip
$SRCZSIP=$SRCFILE_PATH."/configmanager/zsip.txt";
$DSTZSIP=$DSTFILE_PATH."/zsip.txt";
file_put_contents($SRCZSIP,'zabbixserverip:'.$ZSIP);

//获取所有fileserver的IP，并写入server.txt中，一并传输
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

//传输打好的包
$command="/bin/sh ".$SCRIPTS_DIR."/csc_mfs_tar_install.sh ".$DESTIP." ".$DESTPASS." ms ".$ZSIP;
exec($command,$output1,$res1); //return value: 0/1/2/3

if($res1==0)//说明传输成功
{
	//传输文件
	$command="/bin/sh ".$SCRIPTS_DIR."/csc_mfs_file_install.sh ".$SRCFILE." ".$DESTIP." ".$DESTPASS." $DSTFILE";
	exec($command,$output2,$res2);
		
	if($res2==0)
	{////执行脚本文件
		$command="/bin/sh ".$SCRIPTS_DIR."/csc_mfs_exec_script.sh ".$DESTIP." ".$DESTPASS." ms ".$exec_script;
		exec($command,$output3,$res3);
		echo "传输包文件的返回值为：".$res1."<br>传输单个文件的返回值为：".$res2."<br>执行脚本的返回值为".$res3;
		
		if($res3==0)	
		{
			$command="/bin/sh ".$SCRIPTS_DIR."/csc_mfs_file_install.sh ".$SRCZSIP." ".$DESTIP." ".$DESTPASS." $DSTZSIP";
			exec($command,$output4,$res4);
		if($res4 == 0){
			echo "successfully!";
			echo "<a href='installserver.php'>请返回</a>";
			}
			else{
			echo "执行脚本出错";
			echo"res3:".$res4;
			echo "<a href='installserver.php'>请返回</a>";	
			}
		}
		else
		{
			echo "执行脚本出错";
			echo"res3:".$res3;
			echo "<a href='installserver.php'>请返回</a>";
		}
	}
	else
	{
		echo "传输文件出错";
		echo"res3:".$res3;
		echo "<a href='installserver.php'>请返回</a>";
	}
}		
else
{
	echo "传输打好的包出错";
	echo "res1:".$res1;
	echo "<a href='installserver.php'>请返回</a>";
//	fclose($fp);
}

 //return value: 0/1




?>
