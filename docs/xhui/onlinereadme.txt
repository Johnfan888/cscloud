�������
��1���޸�apache�����ļ���
     vi /etc/apache2/default-server.conf
     �����

	Alias /data/ "/data/"     //����/data/Ϊ�ļ��ĸ�Ŀ¼
	<Directory "/data">
       	 	AllowOverride None
        	Options None
        	order allow,deny
        	Allow from all
	</Directory>	
 
��3���޸�/srv/www/htdocs/themes/default/home.html�ļ�
��2����fs �����Ŀ¼ mkdir /srv/www/htdocs/OnlinePlayFile����������ļ���
��3����fs ��/srv/www/htdocs/www/����� ds_online_play.php�ļ�
��4����ms��/srv/www/htdocs/�����ms_online_play.php�ļ�