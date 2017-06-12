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
<h1>用户目录空间列表</h1> 
<table border="0" align="center" cellpadding="0" cellspacing="0">
<form method="post" action="spaceinfo.php">
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
		<tr><td colspan="6"><p>各用户目录空间大小(表filesize)</p></td></tr>
		<tr>
			<td align='center'>username</td>
			<td align='center'>serverip</td>
			<td align='center'>usedsize(MB)</td>
			<td align='center'>totalsize(MB)</td>
			<td align='center'>filenumber</td>
			<td align='center'>dirnumber</td>
		</tr>

<?php
	require("../include/comment.php");
	require("../include/user.class.php");
	$user =&username::getInstance();
if($flag==1)
{	
	$sql="select * from filesize";
}
else if($flag==0)
{
	$sql="select * from filesize where username='".$_POST["username"]."'";
}
		$res=mysql_query($sql,$user->Con1);
		$numrows=mysql_num_rows($res);
		if($numrows>0)
		{
			for($rows=0;$rows<$numrows;$rows++)             
			{    
				$menu=mysql_fetch_array($res);
				$username=$menu["username"];
				$sql="select user_id from members where username='".$menu["username"]."'";
				$res1=mysql_query($sql,$user->Con1);
				$menu1=mysql_fetch_array($res1);
				
				//计算用户的文件数
				$sql="select * from tb_file_all where user_id='".$menu1["user_id"]."' and filetype='0'";
				$res2=mysql_query($sql,$user->Con1);
			    $filenumber=mysql_num_rows($res2);
					
			
				//计算用户的目录数	          
				$sql="select * from tb_file_all where user_id='".$menu1["user_id"]."' and filetype='1'";
				$res3=mysql_query($sql,$user->Con1);
			    $dirnumber=mysql_num_rows($res3);
							  
				echo "<tr><td align='center'>".$menu['username']."</td>
					<td align='center'>".$menu['serverip']."</td>
					<td align='center'>".round($menu['usedsize']/1000000,2)."</td>
					<td align='center'>".round($menu['totalsize']/1000000,2)."</td>
					<td align='center'>".$filenumber."</td>
					<td align='center'>".$dirnumber."</td>
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
