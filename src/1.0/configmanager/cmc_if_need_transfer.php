<?php
header('Content-Type:text/html;charset=gb2312');        //���ñ���
error_reporting(0);
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
require("/srv/www/htdocs/configmanager/conn/conn.php");
require("/srv/www/htdocs/configmanager/Transfer_strategy.php");
//require_once('/srv/www/htdocs/configmanager/sendmail/socketsendmail.php');
$flag=1;
$fp=fopen("/srv/www/htdocs/configmanager/flag.txt","w");
fwrite($fp,$flag);
fclose($fp);
//}
//���ж��Ƿ���Ǩ���������ڽ��У�����еĻ����������������û�У���鿴�Ƿ���ҪǨ�ơ�
$fp=fopen("/srv/www/htdocs/configmanager/flag.txt","r");
$flag=fread($fp,filesize("/srv/www/htdocs/configmanager/flag.txt"));//��ȡ
//echo "flag:".$flag;
fclose($fp);
if($flag==1)
{//˵������Ҫ�鿴�Ƿ�Ǩ��
	$sql_queue="select * from QUEUE where QUEUENAME='transfer'";
	$result=mysql_query($sql_queue,$conne->getconnect());
	$statusmenu=mysql_fetch_array($result);
	$status=$statusmenu["STATUS"];
	if($status=='1'){
		echo"Data migration exists��please wait!";
	}
	if($status=='0') //Ϊ0ʱ��ʾû��������Ǩ��
	{
			$max=max;
			$transfer=new TransferServer;
			$load=$transfer->LoadExceed();
			if($load == 'needless'){
			    echo "All system loads are normal!";
			}
			elseif($load == 'loadexceed'){
				echo "The overall system is overloaded,please add server!";
			}
			else{
			$maxvalue=$transfer->FindTransferServer($max);
			$menu["ip_address"]=$maxvalue[0];
			$menu["loading"]=$maxvalue[1];
			if(selectsourceserver($menu["loading"])=="need") //ѡ��Դ���ݷ�����
			{
				$fp=fopen("/srv/www/htdocs/configmanager/transfer_ip.txt","w");
				fwrite($fp,$menu["ip_address"]."\n");
				//����Ŀ�������
				$min=min;
				$minvalue=$transfer->FindTransferServer($min); //�˴����жϸ���������
				$ip=$minvalue[0]; //Ŀ�������
				$hostid=$minvalue[2]; //add
				//��ȡĿ��������������� byte
				$totalsize=$transfer->TargetServerTotalSize($hostid); //add
				//echo "totalsize".$totalsize;
				//��ȡĿ���������ʹ������  byte
				$usedsize=$transfer->TargetServerUsedSize($hostid); //add
				//echo "usedsize".$usedsize;
				fwrite($fp,$ip."\n");
				$sql1="select ip_address from ip_table where status='manager'";
				$res1=mysql_query($sql1,$conne->getconnect());
				$menu1=mysql_fetch_array($res1);
				$managerip=$menu1["ip_address"];
				//��ȡǨ�Ƹ���ֵ
				//��ȡ����ֵ
				$c = new Configuration();
				$c->configFile="/srv/www/htdocs/configmanager/config/config.txt";
				$c->_construct();
				$threshold=$c->_get("Loading_threshold");
				$step=1;//�����ʺϵ��û�id��
				$url="http://".$managerip."/manage/ms_Transfer_Info.php?originip=".$menu["ip_address"]."&step=".$step."&targetip=".$ip."&totalsize=".$totalsize."&usedsize=".$usedsize."&threshold=".$threshold;
				$ch=curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
				$result1=curl_exec($ch);  //���ҪǨ�Ƶ��û�id
				curl_close($ch);
			    $result1=json_decode($result1);
				$count=$result1[0];
				$userid=$result1[1];
				$flag=$result1[3];
				//��ȡ���û�ʹ�õ�ֵ
				//$userusedsize=$result1[2];  //add
				fwrite($fp, $userid."\n");
				fclose($fp);
				if($count == 1)	//����Ƿ����Ǩ������������Ϊһ���û�;
				{
//					$originip1=$menu['ip_address'];
					exit( "server".$menu["ip_address"]."only one user, do not transfer!");

				}
				//if($userid==""){
				elseif($userid=="" && $flag==2){
				exit("server".$menu["ip_address"]."under the heavy load,but there is no user to migrate data to the target server".$ip);

				}
				elseif($userid=="" && $flag=='1'){
				    exit("server".$menu["ip_address"]."under teh heavy load,if migration the target server will exceed the threshold");

				}
				else{
		        $fp=fopen("/srv/www/htdocs/configmanager/flag.txt","w");
				$flag=fwrite($fp,'0');  //Ǩ�ƹ�����Ϊˢ�´�ֵˢ��Ϊ0
				fclose($fp);
                $re=exec("php /srv/www/htdocs/configmanager/cmc_sendtransferip.php");
                echo $re;
//                exit($re);
                }
		}
	}
}
}
?>