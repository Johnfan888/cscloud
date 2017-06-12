<?php 
$method=$_POST["monitoring_method"]; //迁移方法
$Inquire_time=trim($_POST["inquire_time"]);//时间间隔
$Loading_threshold=trim($_POST["loading_threshold"]);//负载
$Maxloading_threshold=trim($_POST["maxloading_threshold"]);//最大负载阈值
require("configure_class.php");
$c = new Configuration();
$c->configFile="/srv/www/htdocs/configmanager/config/config.txt"; //存储迁移的方式
$c->_construct();
echo "<font size='4:px'><strong>method:".$c->_set(Method,$method)."</strong></font><br>";
echo "<font size='4:px'><strong>负载阈值:".$c->_set(Loading_threshold,$Loading_threshold)."</strong></font><br>";
echo "<font size='4:px'><strong>迁移查询间隔时间:".$c->_set(Inquire_time,$Inquire_time)."</strong></font><br>";
echo "<font size='4:px'><strong>最大负载阈值:".$c->_set(MaxLoading_threshold,$Maxloading_threshold)."</strong></font><br>";
echo "";
echo "<a href='minitoring.php'>请返回！</a>";
$c->save();
//把crontab文件删除，重写crontab
system("/usr/bin/sudo /usr/bin/crontab -r"); //执行命令，并输出结果
system("/usr/bin/sudo /bin/rm /srv/www/htdocs/configmanager/monitoring");
if($method=='0')//证明是自动
	{
		$fp=fopen("monitoring","w");
		$string="*/".$Inquire_time." * * * * /usr/bin/php /srv/www/htdocs/configmanager/if_need_transfer_auto.php"; //执行迁移操作
		fwrite($fp,$string."\n");
		fclose($fp);
	}
$cmd="/usr/bin/sudo /usr/bin/crontab /srv/www/htdocs/configmanager/monitoring";
system($cmd);

?>