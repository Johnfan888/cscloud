<?php 
$method=$_POST["monitoring_method"]; //迁移方法
$Inquire_time=trim($_POST["inquire_time"]);//时间间隔
$Loading_threshold=trim($_POST["loading_threshold"]);//负载
require("configure_class.php");
$c = new Configuration();
$c->configFile="/srv/www/htdocs/configmanager/config/config.txt"; //存储迁移的方式
$c->_construct();
echo "<font size='4:px'><strong>method:".$c->_set(Method,$method)."</strong></font><br>";
echo "<font size='4:px'><strong>负载阈值:".$c->_set(Loading_threshold,$Loading_threshold)."</strong></font><br>";
echo "<font size='4:px'><strong>迁移查询间隔时间:".$c->_set(Inquire_time,$Inquire_time)."</strong></font><br>";
echo "<a href='minitoring.php'>请返回！</a>";
$c->save();
//把crontab文件删除，重写crontab
system("/usr/bin/sudo /usr/bin/crontab -r"); //执行命令，并输出结果
system("/usr/bin/sudo /bin/rm /srv/www/htdocs/configmanager/monitoring");
if($method=='0')//证明是自动
	{
		$fp=fopen("monitoring","w");
		$string="*/5 * * * * /usr/bin/php /srv/www/htdocs/configmanager/read_monitor_log.php"; //收集到数据去更新数据库  需要/srv/www/htdocs/mrtg/192.168.1.126_disk.old文件
		fwrite($fp,$string."\n");
		//不用mrtg进行显示
		//$string="*/5 * * * * /usr/bin/mrtg /srv/www/htdocs/mrtg/mrtg.cfg"; //生成mrtg图，用于显示
		//fwrite($fp,$string."\n");
		$string="*/".$Inquire_time." * * * * /usr/bin/php /srv/www/htdocs/configmanager/if_need_transfer_auto.php"; //执行迁移操作
		fwrite($fp,$string."\n");
		fclose($fp);
	}
	else if($method=='1')
	{
		$fp=fopen("monitoring","w");
		$string="*/5 * * * * /usr/bin/php /srv/www/htdocs/configmanager/read_monitor_log.php";
		fwrite($fp,$string."\n");
		//不用mrtg进行显示
		//$string="*/5 * * * * /usr/bin/mrtg /srv/www/htdocs/mrtg/mrtg.cfg";
		//fwrite($fp,$string."\n");
		fclose($fp);
	}
$cmd="/usr/bin/sudo /usr/bin/crontab /srv/www/htdocs/configmanager/monitoring";
system($cmd);
//生成mrtg相关文件
$cmd="/usr/bin/sudo /usr/bin/php /srv/www/htdocs/configmanager/mrtg_configer.php";
system($cmd);

?>