<?php
header("content-type:text/html;charset=utf-8");         //���ñ���
error_reporting(0);
$SCRIPTS_DIR="/cscloud_install_source";
$SRCFILE_PATH="/cscloud_install_source/cscloud/src/1.0";
$DSTFILE_PATH="/srv/www/htdocs";
$DESTPASS="111111";
$MANAGERPASS='111111';
$exec_script="fileserver_setup.php";
$exec_m_script="update_dataserid.php";
//$ZSIP=$_SERVER["SERVER_ADDR"];  //ǰ�˵����Ĳ���
//------------------Ϊ�˴Ӻ�̨���п��Եõ�ip
exec("/bin/sh  /srv/www/htdocs/configmanager/getlocalip.sh",$array);
echo $array[0];
$localip=explode("/",$array[0]);
$localip=$localip[0];
$localip=trim($localip,"\'");
//-----------------------
$ZSIP=$localip;

require("configure_class.php");
require("conn/conn.php");
$config=new Configuration();
$config->configFile=$SRCFILE_PATH."/fileserver/www/config/config.txt";
$config->_construct();
//echo $config->_get("ServerIP");
//��ȡconfigmanager�����ݿ�����ݣ��޸������ļ�������
$sql="select * from ip_table where status='manager'";
$res=mysql_query($sql,$conne->getconnect());
$menu=mysql_fetch_array($res);
$ManagerServerIP=$menu["ip_address"];
$config->_set(ManagerServerIP,$ManagerServerIP);//�޸�manager��ip

$sql="select * from ip_table where status='file' and install_flag='0'";
$result=mysql_query($sql,$conne->getconnect());
$nums=mysql_num_rows($result);
$flag=0;
for($i=0;$i<$nums;$i++)
{
//�޸������ļ�
	$menu=mysql_fetch_array($result);
	$ServerIP=$menu["ip_address"];
	$ServerID=$menu["id"];
	$UserFilePath=$menu["userfilepath"];
	$post_size=$menu["post_size"];
	//$configserverip=$_SERVER['SERVER_ADDR'];
	$configserverip=$ZSIP;
	$config->_set(ServerIP,$ServerIP);
	$config->_set(ServerID,$ServerID);
	$config->_set(UserFilePath,$UserFilePath);
	$config->_set(Configserver,$configserverip);
	$config->_set(Post_size,$post_size);
	$config->save();
	
//�����õİ�
	$SRCFILE=$config->configFile;
	$DSTFILE=$DSTFILE_PATH."/www/config/config.txt";
	$DESTIP=$ServerIP;
	$command="/bin/sh ".$SCRIPTS_DIR."/csc_mfs_tar_install.sh ".$DESTIP." ".$DESTPASS." fs ".$ZSIP; //���zabbixserver������ip��ַ
	exec($command,$output1,$res1); //return value: 0/1/2/3
	if($res1==0)//������ɹ�
	{
//�����ļ�
		$command="/bin/sh ".$SCRIPTS_DIR."/csc_mfs_file_install.sh ".$SRCFILE." ".$DESTIP." ".$DESTPASS." $DSTFILE";
		exec($command,$output2,$res2);
		if($res2==0)
		{
	//ִ�нű��ļ�
			$command="/bin/sh ".$SCRIPTS_DIR."/csc_mfs_exec_script.sh ".$DESTIP." ".$DESTPASS." fs ".$exec_script;
			exec($command,$output3,$res3);
			if($res3==0){
				echo $DESTIP."successfully!";
				$SQL="update ip_table set install_flag='1' where ip_address='".$DESTIP."'";
				$result1=mysql_query($SQL,$conne->getconnect());
				//�����ļ���������Ϣ��ӵ��޸����ݿ�Ľű���
				if($result1){  //��װ�ɹ�����ms���ݿ�
					$step=3;
					$url="http://".$ManagerServerIP."/manage/ms_Transfer_Info.php?step=".$step."&ServerIP=".$ServerIP."&UserFilePath=".$UserFilePath;
					$ch=curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
					$output=curl_exec($ch);
					curl_close($ch);
				}
				else{
					echo $DESTIP."�������ù�����������ݿ����";
				}
			}
			else{
				echo $DESTIP."ִ�нű�����,�������res3��".$res3;
			}
		}
		else{
			echo $DESTIP."�����ļ�����,�������res2��".$res2;;
		}
	}
	else{
		echo $DESTIP."�����ļ������������res1��".$res1;
		}
	
	
	
}
//	echo "<a href='installserver.php'>�뷵��</a>";
 ?>