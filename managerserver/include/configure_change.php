<?php 
$Cachecount=$_POST['Cachecount'];
$configip=$_POST['configip'];
$serverip_m=$_POST['ip_m'];
?>
<?php
require("configure_class.php");
$c = new Configuration();
$c->_construct();

//ȡ���ļ��м���ΪName��ֵ
echo "<font size='4:px'><strong>after modification :</strong></font><br>";
echo "<font size='4:px'><strong>Cachecount:".$c->_set(Cachecount,$Cachecount)."</strong></font><br>";
echo "<font size='4:px'><strong>serverip:".$c->_set(ServerIP_M,$serverip_m)."</strong></font><br>";
$c->save();

?>
<?php echo "<a href='http://".$_POST['configip']."/configmanager/configserver.php'>���óɹ����뷵�أ�</a>";?>
