<?php 
$ip=$_POST['ip'];
$id=$_POST['id'];
$managerip=$_POST['managerip'];
$configip=$_POST['configip'];
$userfilepath=$_POST['filepath'];
?>
<?php
require("configure_class.php");
$c = new Configuration();
$c->_construct();

//取得文件中键名为Name的值
echo "<font size='4:px'><strong>after modification :</strong></font><br>";
echo "<font size='4:px'><strong>serverip:".$c->_set(ServerIP,$ip)."</strong></font><br>";
echo "<font size='4:px'><strong>serverid:".$c->_set(ServerID,$id)."</strong></font><br>";
echo "<font size='4:px'><strong>serverid:".$c->_set(ManagerServerIP,$managerip)."</strong></font><br>";
echo "<font size='4:px'><strong>serverid:".$c->_set(UserFilePath,$userfilepath)."</strong></font><br>";
$c->save();

?>
<?php echo "<a href='http://".$_POST['configip']."/configmanager/configserver.php'>配置成功，请返回！</a>";?>
