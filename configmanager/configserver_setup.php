<?php
system("cp -r ../configmanager /srv/www/htdocs/");

$dir="/srv/www/htdocs/configmanager";
chown($dir,"wwwrun");//�ı�������
chgrp($dir,"www");//�ı���������

//ִ��new.sql�������ݿ�
mysql_connect(); 
$ar   =   split( ";",join( " ",file( "/srv/www/htdocs/configmanager/database.sql"))); 
foreach($ar   as   $v) 
mysql_query($v); 


?>