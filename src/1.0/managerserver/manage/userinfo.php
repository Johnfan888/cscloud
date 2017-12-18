 <?php
if(!$_COOKIE['admin']['user_id']){
	echo "未登陆";
	exit();
 }
require("configure_class.php");
$c = new Configuration();
$c->_construct();
if(!isset($_POST["username"]))
{
	$flag=1;
}
else{
	$flag=0;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
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
<h1>用户信息列表</h1> 
<table border="0" align="center" cellpadding="0" cellspacing="0">
<form method="post" action="userinfo.php">
	<tr>
		<td width="37%">
			用户名：
			  <input type="text" name="username">
	  </td>
		<td width="63%">
			<input type="submit" value="submit">
	  </td>
    </tr>
</form>	
</table>


<table width="90%">
<tr>
   <td width='40%'>
	<table border="0" cellspacing="0" cellpadding="0" align="center" class=Navi>
		<tr><td colspan="6"><p>各用户信息(表members)</p></td></tr>
		<tr>
			<td align='center'>username</td>
			<td align='center'>email</td>
			<td align='center'>company</td>
			<td align='center'>country</td>
			<td align='center'>tel</td>
			<td align='center'>fax</td>
		</tr>

<?php
	require("../include/comment.php");
	require("../include/user.class.php");
	$user =&username::getInstance();
if($flag==1)
{	
	$sql="select * from members";
}
else if($flag==0)
{
	$sql="select * from members where username='".$_POST["username"]."'";
}
		$res=mysql_query($sql,$user->Con1);
		$numrows=mysql_num_rows($res);
		if($numrows>0)
		{
			for($rows=0;$rows<$numrows;$rows++)             
			{    
				$menu=mysql_fetch_array($res);
				  
				echo "<tr><td align='center'>".$menu['username']."</td>
					<td align='center'>".$menu['email']."</td>
					<td align='center'>".$menu['company']."</td>
					<td align='center'>".$menu['country']."</td>
					<td align='center'>".$menu['tel']."</td>
					<td align='center'>".$menu['fax']."</td>
					</tr>";
					  }
					}

         ?>
    </table>
    </td>
 </tr>
</table>
</body>
</html>
