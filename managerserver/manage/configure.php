
 <?php
if(!$_COOKIE['admin']['user_id']){
	echo "δ��½";
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
<!--�ļ�Ǩ����Ϣ����-->  
<h1>�ļ�Ǩ������</h1> 
 <form method="post" action="configure_Migrattion.php">
	<table border="0" cellspacing="0" cellpadding="0" align="center" class=Navi>
		<tr><td>
		��������<input name="pass" type="text" value="<?php echo $c->_get("Password") ?>"/>
		(�����Ҫ�������룬�����������룻�������Ҫ�������룬��ϵͳ���Զ���������)
		</td></tr> 
	 
		<tr><td>
			�Ƿ���Ҫ˫���� 
		  <input name="Need_backup" type="text" value="<?php echo $c->_get("Need_backup") ?>" />
		  (��0����ʾ��Ҫ����1����ʾ����Ҫ)
		</td></tr>
		<tr><td>
			�û��ļ�˫����ʱ���� 
		  <input name="Time_interval" type="text" value="<?php echo $c->_get("Time_interval") ?>" />(��λ������)
			</td></tr>
		<tr><td>
		<input name="modifybutton" type="submit" value="modify" />
		</td></tr>
	</table>
</form>
</body>   
</html>  
