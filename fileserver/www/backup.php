<?php 
$oldname=trim($_GET['oldid']);
$newname=trim($_GET['newid']);
$path=$_GET['path'];
$filepath=dirname($path);
//�����Ƿ���ȷȡ��������ֵ
/*$fp=fopen("backup.txt","w");
fwrite($fp,$oldname."\n");
fwrite($fp,$newname."\n");
fwrite($fp,$filepath);
fclose($fp);*/

chdir($filepath);//�л�Ŀ¼��ָ�����ļ�Ŀ¼��
system('/usr/bin/sudo /usr/bin/diff -auN '.$oldname.' '.$newname.' >'.$newname.'.patch');
//ȥ���м��patch�ļ����õڶ����ļ�����������patch�ļ�start-------------------------------------------
system('/usr/bin/sudo /bin/rm '.$newname);
$newpath=$filepath."/".$newname.'.patch';
rename($newpath,$path);
//end-------------------------------------------------------------------------------------------------
/*//����
$fp=fopen("aa.txt","w");
fwrite($fp,$newpath."\n");
fwrite($fp,$path."\n");
fclose($fp);*/

 ?>