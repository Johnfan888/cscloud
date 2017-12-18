 <?php
if(!$_COOKIE['admin']['user_id']){
	echo "未登陆";
	exit();
 }
?>
<?php 
$pass=$_POST['pass'];
$time_interval=$_POST['Time_interval'];
$need_backup=$_POST["Need_backup"];//"0"表示需要，"1"表示不需要
?>
<?php
require("configure_class.php");
$c = new Configuration();
$c->_construct();

//取得文件中键名为Name的值

echo "<font size='4:px'><strong>after modification :</strong></font><br>";
echo "<font size='4:px'><strong>Password:".$c->_set("Password",$pass)."</strong></font><br>";
echo "<font size='4:px'><strong>Time_interval:".$c->_set("Time_interval",$time_interval)."</strong></font><br>";
echo "<font size='4:px'><strong>Need_backup:".$c->_set("Need_backup",$need_backup)."</strong></font><br>";
$c->save();

if($need_backup=="0")
{
system("/usr/bin/sudo ./backup");
}
else if($need_backup=="1")
{
system("/usr/bin/sudo killall -9 backup");
}
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
