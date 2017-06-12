<?php
require("../configure_class.php");
							$c = new Configuration();
							$c->configFile="/srv/www/htdocs/configmanager/config/mailinfo_config.txt";
							$c->_construct();
								if(isset($_POST['server'])){
									$server=$_POST['server'];
									$port=$_POST['port'];
									$from=$_POST['from'];
									$passwd=$_POST['passwd'];
									$to=$_POST['to'];
									
									echo "<font size='4:px'><strong>邮件服务器:".$c->_set(Server, $server)."</strong></font><br>";
									echo "<font size='4:px'><strong>监听端口:".$c->_set(Port, $port)."</strong></font><br>";
									echo "<font size='4:px'><strong>发送方邮箱地址:".$c->_set("From", $from)."</strong></font><br>";
									echo "<font size='4:px'><strong>密码:".$c->_set(Passwd, $passwd)."</strong></font><br>";
									echo "<font size='4:px'><strong>接收方地址:".$c->_set(To, $to)."</strong></font><br>";
									echo "";
									echo "<a href='mailinfo_config.php'>请返回！</a>";
									}
									$c->save();
									?>