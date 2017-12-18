<?php 
$oldname=trim($_GET['oldid']);
$newname=trim($_GET['newid']);
$path=$_GET['path'];
$filepath=dirname($path);
//测试是否正确取到变量的值
/*$fp=fopen("backup.txt","w");
fwrite($fp,$oldname."\n");
fwrite($fp,$newname."\n");
fwrite($fp,$filepath);
fclose($fp);*/

chdir($filepath);//切换目录到指定的文件目录下
system('/usr/bin/sudo /usr/bin/diff -auN '.$oldname.' '.$newname.' >'.$newname.'.patch');
//去掉中间的patch文件，用第二个文件的名字命名patch文件start-------------------------------------------
system('/usr/bin/sudo /bin/rm '.$newname);
$newpath=$filepath."/".$newname.'.patch';
rename($newpath,$path);
//end-------------------------------------------------------------------------------------------------
/*//测试
$fp=fopen("aa.txt","w");
fwrite($fp,$newpath."\n");
fwrite($fp,$path."\n");
fclose($fp);*/

 ?>