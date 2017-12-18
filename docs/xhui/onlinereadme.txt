解决方案
（1）修改apache配置文件：
     vi /etc/apache2/default-server.conf
     中添加

	Alias /data/ "/data/"     //其中/data/为文件的根目录
	<Directory "/data">
       	 	AllowOverride None
        	Options None
        	order allow,deny
        	Allow from all
	</Directory>	
 
（3）修改/srv/www/htdocs/themes/default/home.html文件
（2）在fs 下添加目录 mkdir /srv/www/htdocs/OnlinePlayFile（存放链接文件）
（3）在fs 的/srv/www/htdocs/www/下添加 ds_online_play.php文件
（4）在ms的/srv/www/htdocs/下添加ms_online_play.php文件