#usr/bin/php
<?php 
//����logĿ¼
require("/srv/www/htdocs/include/config.php");
$dir="/var/log/csc";
if(!is_dir($dir))
{
	mkdir($dir,0777);
}
chown($dir,"wwwrun");//�ı�������
chgrp($dir,"www");//�ı���������

$file="/srv/www/htdocs/manage/backup";
chmod($file,"0755");
//ִ��backup
chdir("/srv/www/htdocs/manage/");

//�޸�smartyģ���Ŀ¼Ȩ��
$dir="/srv/www/htdocs/smarty/templates_c";
chown($dir,"wwwrun");//�ı�������
chgrp($dir,"www");//�ı���������

$dir="/srv/www/htdocs/manage/fileserver.txt";
chown($dir,"wwwrun");//�ı�������
chgrp($dir,"www");//�ı���������

//�޸����ݿ�����
//�������ݿ� �Ĺ����ڽ�����ִ�У�����Ҫ
/*system("/usr/bin/sudo /usr/bin/mysqladmin -u root password '111111'");

//ִ��new.sql�������ݿ�
mysql_connect($CONF['db']['host'],$CONF['db']['user'],$CONF['db']['pwd']); 
$ar   =   split( ";",join( " ",file( "/srv/www/htdocs/create.sql"))); 
foreach($ar   as   $v) 
mysql_query($v); */


?>