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
									
									echo "<font size='4:px'><strong>�ʼ�������:".$c->_set(Server, $server)."</strong></font><br>";
									echo "<font size='4:px'><strong>�����˿�:".$c->_set(Port, $port)."</strong></font><br>";
									echo "<font size='4:px'><strong>���ͷ������ַ:".$c->_set("From", $from)."</strong></font><br>";
									echo "<font size='4:px'><strong>����:".$c->_set(Passwd, $passwd)."</strong></font><br>";
									echo "<font size='4:px'><strong>���շ���ַ:".$c->_set(To, $to)."</strong></font><br>";
									echo "";
									echo "<a href='mailinfo_config.php'>�뷵�أ�</a>";
									}
									$c->save();
									?>