<?php
system("cp -r ../configmanager /srv/www/htdocs/");

$dir="/srv/www/htdocs/configmanager";
chown($dir,"wwwrun");//改变所有者
chgrp($dir,"www");//改变所属的组

//执行new.sql创建数据库
mysql_connect(); 
$ar   =   split( ";",join( " ",file( "/srv/www/htdocs/configmanager/database.sql"))); 
foreach($ar   as   $v) 
mysql_query($v); 


?>