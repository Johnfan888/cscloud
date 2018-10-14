#usr/bin/php
<?php 
//创建log目录
require("configure_class.php");
$c = new Configuration();
$c->_construct();

$dir="/var/log/csc";
if(!is_dir($dir))
{
	mkdir($dir,0777);
}
chown($dir,"wwwrun");//改变所有者
chgrp($dir,"www");//改变所属的组

//创建用户文件存储目录
$dir=$c->_get(UserFilePath);
$post_size=$c->_get(Post_size);
if(!is_dir($dir))
{
	mkdir($dir,0777);
}
chown($dir,"wwwrun");
chgrp($dir,"www");

//文件多版本管理使用命令时的权限问题
$fp=fopen("/etc/sudoers","a");
fwrite($fp,"wwwrun ALL=(ALL)NOPASSWD:ALL\n");
fclose($fp);



//修改snmp的配置文件
$fp=fopen("/etc/snmp/snmpd.conf","a");
$cmd="rocommunity public ".$webserverip=trim($c->_get("Configserver"));
fwrite($fp,$cmd."\n");
fwrite($fp,"exec .1.3.6.1.4.1.1111.53 mfree /bin/sh /etc/snmp/mfree.sh\n");
fwrite($fp,"exec .1.3.6.1.4.1.1111.54 cpustat /bin/sh /root/cpustat.sh\n");
fwrite($fp,"exec .1.3.6.1.4.1.1111.55 dfree /bin/sh /etc/snmp/dfree.sh\n");
fclose($fp);
//重启snmp服务
system("/usr/bin/sudo /sbin/service snmpd restart");

//修改apache配置文件
$fpp=fopen("/etc/php5/apache2/php.ini","a");
fwrite($fpp, "\n;csc configuration\n");
fwrite($fpp, "upload_max_filesize = $post_size\n");
fwrite($fpp, "post_max_size = $post_size\n");
fclose($fpp);
//重启服务
system("/usr/bin/sudo /sbin/service apache2 restart");

//把添加的snmp节点的执行文件拷到/etc/snmp/下
system("/usr/bin/sudo /bin/cp /srv/www/htdocs/www/mfree.sh /etc/snmp/");
system("/usr/bin/sudo /bin/cp /srv/www/htdocs/www/cpustat.sh /etc/snmp/");
system("/usr/bin/sudo /bin/cp /srv/www/htdocs/www/dfree.sh /etc/snmp/");
?>
