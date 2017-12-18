#usr/bin/php
<?php 
//创建log目录
require("/srv/www/htdocs/include/config.php");
$dir="/var/log/csc";
if(!is_dir($dir))
{
	mkdir($dir,0777);
}
chown($dir,"wwwrun");//改变所有者
chgrp($dir,"www");//改变所属的组

$file="/srv/www/htdocs/manage/backup";
chmod($file,"0755");
//执行backup
chdir("/srv/www/htdocs/manage/");

//修改smarty模板的目录权限
$dir="/srv/www/htdocs/smarty/templates_c";
chown($dir,"wwwrun");//改变所有者
chgrp($dir,"www");//改变所属的组

$dir="/srv/www/htdocs/manage/fileserver.txt";
chown($dir,"wwwrun");//改变所有者
chgrp($dir,"www");//改变所属的组

//修改数据库密码
//创建数据库 的过程在界面上执行，不需要
/*system("/usr/bin/sudo /usr/bin/mysqladmin -u root password '111111'");

//执行new.sql创建数据库
mysql_connect($CONF['db']['host'],$CONF['db']['user'],$CONF['db']['pwd']); 
$ar   =   split( ";",join( " ",file( "/srv/www/htdocs/create.sql"))); 
foreach($ar   as   $v) 
mysql_query($v); */


?>