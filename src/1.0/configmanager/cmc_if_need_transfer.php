<?php
header('Content-Type:text/html;charset=gb2312');        //设置编码
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
//先判断是否有迁移任务正在进行，如果有的话，不做操作；如果没有，则查看是否需要迁移。
$fp=fopen("/srv/www/htdocs/configmanager/flag.txt","r");
$flag=fread($fp,filesize("/srv/www/htdocs/configmanager/flag.txt"));//读取
//echo "flag:".$flag;
fclose($fp);
if($flag==1)
{//说明是需要查看是否迁移
	$sql_queue="select * from QUEUE where QUEUENAME='transfer'";
	$result=mysql_query($sql_queue,$conne->getconnect());
	$statusmenu=mysql_fetch_array($result);
	$status=$statusmenu["STATUS"];
	if($status=='1'){
		echo"Data migration exists，please wait!";
	}
	if($status=='0') //为0时表示没有数据在迁移
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
			if(selectsourceserver($menu["loading"])=="need") //选择源数据服务器
			{
				$fp=fopen("/srv/www/htdocs/configmanager/transfer_ip.txt","w");
				fwrite($fp,$menu["ip_address"]."\n");
				//查找目标服务器
				$min=min;
				$minvalue=$transfer->FindTransferServer($min); //此处不判断副本服务器
				$ip=$minvalue[0]; //目标服务器
				$hostid=$minvalue[2]; //add
				//获取目标服务器的总容量 byte
				$totalsize=$transfer->TargetServerTotalSize($hostid); //add
				//echo "totalsize".$totalsize;
				//获取目标服务器已使用容量  byte
				$usedsize=$transfer->TargetServerUsedSize($hostid); //add
				//echo "usedsize".$usedsize;
				fwrite($fp,$ip."\n");
				$sql1="select ip_address from ip_table where status='manager'";
				$res1=mysql_query($sql1,$conne->getconnect());
				$menu1=mysql_fetch_array($res1);
				$managerip=$menu1["ip_address"];
				//获取迁移负载值
				//获取负载值
				$c = new Configuration();
				$c->configFile="/srv/www/htdocs/configmanager/config/config.txt";
				$c->_construct();
				$threshold=$c->_get("Loading_threshold");
				$step=1;//查找适合的用户id；
				$url="http://".$managerip."/manage/ms_Transfer_Info.php?originip=".$menu["ip_address"]."&step=".$step."&targetip=".$ip."&totalsize=".$totalsize."&usedsize=".$usedsize."&threshold=".$threshold;
				$ch=curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
				curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
				$result1=curl_exec($ch);  //获得要迁移的用户id
				curl_close($ch);
			    $result1=json_decode($result1);
				$count=$result1[0];
				$userid=$result1[1];
				$flag=$result1[3];
				//获取到用户使用的值
				//$userusedsize=$result1[2];  //add
				fwrite($fp, $userid."\n");
				fclose($fp);
				if($count == 1)	//检查是否符合迁移条件，不能为一个用户;
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
				$flag=fwrite($fp,'0');  //迁移过程中为刷新此值刷新为0
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