
 <?php
if(!$_COOKIE['admin']['user_id']){
	echo "未登陆";
	exit();
 }
?>
<?php 

$backuptime=$_POST['backup_time'];


?>
<?php
require("configure_class.php");
$c = new Configuration();
$c->_construct();

//取得文件中键名为Name的值

echo "<font size='4:px'><strong>after modification :</strong></font><br>";
echo "<font size='4:px'><strong>Title:".$c->_set(Title,$title)."</strong></font><br>";
echo "<font size='4:px'><strong>Name:".$c->_set(Name,$name)."</strong></font><br>";
echo "<font size='4:px'><strong>Backup_time:".$c->_set(Backup_time,$backuptime)."</strong></font><br>";
echo "<font size='4:px'><strong>Updownpath:".$c->_set(Updownpath,$updownpath)."</strong></font><br>";

$c->save();

?>
<html>
<title>
</title>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=gbk" />    
   <link rel='stylesheet' type='text/css' href='css/private.css'>           
  <script   language="JavaScript"   src="TreeMenu.js"></script>
<style type="text/css">
<!--
.STYLE1 {
	font-size: 24px;
	font-weight: bold;
	color: #0033CC;
}
-->
</style>
</head>   
<body>
<a href="configure.php"><font size='4:px'><strong>返回</strong></font></a>

</body>
</html>
