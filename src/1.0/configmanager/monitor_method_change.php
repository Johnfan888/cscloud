<?php 
$method=$_POST["monitoring_method"]; //Ǩ�Ʒ���
$Inquire_time=trim($_POST["inquire_time"]);//ʱ����
$Loading_threshold=trim($_POST["loading_threshold"]);//����
$Maxloading_threshold=trim($_POST["maxloading_threshold"]);//�������ֵ
require("configure_class.php");
$c = new Configuration();
$c->configFile="/srv/www/htdocs/configmanager/config/config.txt"; //�洢Ǩ�Ƶķ�ʽ
$c->_construct();
echo "<font size='4:px'><strong>method:".$c->_set(Method,$method)."</strong></font><br>";
echo "<font size='4:px'><strong>������ֵ:".$c->_set(Loading_threshold,$Loading_threshold)."</strong></font><br>";
echo "<font size='4:px'><strong>Ǩ�Ʋ�ѯ���ʱ��:".$c->_set(Inquire_time,$Inquire_time)."</strong></font><br>";
echo "<font size='4:px'><strong>�������ֵ:".$c->_set(MaxLoading_threshold,$Maxloading_threshold)."</strong></font><br>";
echo "";
echo "<a href='minitoring.php'>�뷵�أ�</a>";
$c->save();
//��crontab�ļ�ɾ������дcrontab
system("/usr/bin/sudo /usr/bin/crontab -r"); //ִ�������������
system("/usr/bin/sudo /bin/rm /srv/www/htdocs/configmanager/monitoring");
if($method=='0')//֤�����Զ�
	{
		$fp=fopen("monitoring","w");
		$string="*/".$Inquire_time." * * * * /usr/bin/php /srv/www/htdocs/configmanager/if_need_transfer_auto.php"; //ִ��Ǩ�Ʋ���
		fwrite($fp,$string."\n");
		fclose($fp);
	}
$cmd="/usr/bin/sudo /usr/bin/crontab /srv/www/htdocs/configmanager/monitoring";
system($cmd);

?>