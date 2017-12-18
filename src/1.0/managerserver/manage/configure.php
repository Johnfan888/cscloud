
 <?php
if(!$_COOKIE['admin']['user_id']){
	echo "未登陆";
	exit();
 }
require("configure_class.php");
$c = new Configuration();
$c->_construct();
?>
<html>             
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
<!--文件迁移信息配置-->  
<h1>文件迁移配置</h1> 
 <form method="post" action="configure_Migrattion.php">
	<table border="0" cellspacing="0" cellpadding="0" align="center" class=Navi>
		<tr><td>
		密码设置<input name="pass" type="text" value="<?php echo $c->_get("Password") ?>"/>
		(如果需要设置密码，填入您的密码；如果不需要设置密码，则系统会自动生成密码)
		</td></tr> 
	 
		<tr><td>
			是否需要双备份 
		  <input name="Need_backup" type="text" value="<?php echo $c->_get("Need_backup") ?>" />
		  (“0”表示需要，“1”表示不需要)
		</td></tr>
		<tr><td>
			用户文件双备份时间间隔 
		  <input name="Time_interval" type="text" value="<?php echo $c->_get("Time_interval") ?>" />(单位：分钟)
			</td></tr>
		<tr><td>
		<input name="modifybutton" type="submit" value="modify" />
		</td></tr>
	</table>
</form>
</body>   
</html>  
