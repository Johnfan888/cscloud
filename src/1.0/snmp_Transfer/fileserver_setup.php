#usr/bin/php
<?php 
//����logĿ¼
require("configure_class.php");
$c = new Configuration();
$c->_construct();

$dir="/var/log/csc";
if(!is_dir($dir))
{
	mkdir($dir,0777);
}
chown($dir,"wwwrun");//�ı�������
chgrp($dir,"www");//�ı���������

//�����û��ļ��洢Ŀ¼
$dir=$c->_get(UserFilePath);
$post_size=$c->_get(Post_size);
if(!is_dir($dir))
{
	mkdir($dir,0777);
}
chown($dir,"wwwrun");
chgrp($dir,"www");

//�ļ���汾����ʹ������ʱ��Ȩ������
$fp=fopen("/etc/sudoers","a");
fwrite($fp,"wwwrun ALL=(ALL)NOPASSWD:ALL\n");
fclose($fp);



//�޸�snmp�������ļ�
$fp=fopen("/etc/snmp/snmpd.conf","a");
$cmd="rocommunity public ".$webserverip=trim($c->_get("Configserver"));
fwrite($fp,$cmd."\n");
fwrite($fp,"exec .1.3.6.1.4.1.1111.53 mfree /bin/sh /etc/snmp/mfree.sh\n");
fwrite($fp,"exec .1.3.6.1.4.1.1111.54 cpustat /bin/sh /root/cpustat.sh\n");
fwrite($fp,"exec .1.3.6.1.4.1.1111.55 dfree /bin/sh /etc/snmp/dfree.sh\n");
fclose($fp);
//����snmp����
system("/usr/bin/sudo /sbin/service snmpd restart");

//�޸�apache�����ļ�
$fpp=fopen("/etc/php5/apache2/php.ini","a");
fwrite($fpp, "\n;csc configuration\n");
fwrite($fpp, "upload_max_filesize = $post_size\n");
fwrite($fpp, "post_max_size = $post_size\n");
fclose($fpp);
//��������
system("/usr/bin/sudo /sbin/service apache2 restart");

//����ӵ�snmp�ڵ��ִ���ļ�����/etc/snmp/��
system("/usr/bin/sudo /bin/cp /srv/www/htdocs/www/mfree.sh /etc/snmp/");
system("/usr/bin/sudo /bin/cp /srv/www/htdocs/www/cpustat.sh /etc/snmp/");
system("/usr/bin/sudo /bin/cp /srv/www/htdocs/www/dfree.sh /etc/snmp/");
?>
