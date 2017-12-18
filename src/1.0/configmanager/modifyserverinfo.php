<?php 
	session_start();
	if($_SESSION['name']=="")
	{
	echo "尚未登陆！";
	exit();
	
	}
require("conn/conn.php");
$ip=$_GET["ip"];

$sql="select * from ip_table where ip_address='".$ip."'";
$result=mysql_query($sql,$conne->getconnect());
$menu=mysql_fetch_array($result);
?>
<html>
<head>
	<title>修改服务器信息</title>
<script language="javascript" type="text/javascript"> 
function Returnback()
{
    location.href='configserver.php'; 
}
</script>
	<link href="styles.css" rel="stylesheet" type="text/css" /> 
<style type="text/css"> 
/*body { 
    font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 
    color: #4f6b72; 
    background: #ffffff; 
} */
#mytable { 
    width: 500px; 
    padding: 0; 
    margin: 0; 
} 

caption { 
    padding: 0 0 5px 0; 
    width: 700px;      
    font: italic 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 
    text-align: right; 
} 

th { 
    font: bold 12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 
    color: #4f6b72; 
	border-left: 1px solid #C1DAD7; 
    border-right: 1px solid #C1DAD7; 
    border-bottom: 1px solid #C1DAD7; 
    border-top: 1px solid #C1DAD7; 
    letter-spacing: 2px; 
    text-transform: uppercase; 
    text-align: left; 
    padding: 6px 6px 6px 12px; 
    background: #CAE8EA url(images/bg_header.jpg) no-repeat; 
} 

th.nobg { 
    border-top: 0; 
    border-left: 0; 
    border-right: 1px solid #C1DAD7; 
    background: none; 
} 

/*td { 
    border-left: 1px solid #C1DAD7; 
    border-right: 1px solid #C1DAD7; 
    border-bottom: 1px solid #C1DAD7; 
    background: #fff; 
    font-size:11px; 
    padding: 6px 6px 6px 12px; 
    color: #4f6b72; 
} */


td.alt { 
    background: #F5FAFA; 
    color: #797268; 
} 

.STYLE1 {
	font-size: medium;
	color: #000000;
	font-weight: bold;
}
</style> 
</head>

<BODY LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0" BGCOLOR="#FFFFFF">
<?php echo '欢迎光临！'.$_SESSION['name']; echo"   <a href=\"loginout.php\">退出系统</a>"?>
<TR>
    <Td width="967" valign="top"> 
      <table cellpadding="0" cellspacing="0" width="100%" height="108"><Td width="780" height="108" valign="top"><img src="images/topbar.jpg" width="100%" height="108"></Td></table>

<table cellpadding="0" cellspacing="0" width="960">
<Td width="170" valign="top">
<img src="images/menutop.jpg" width="170" height="45"><a href="configserver.php"><img src="images/button-1.jpg" width="170" height="49" border="0"></a><a href="installserver.php"><img src="images/button-2.jpg" width="170" height="52" border="0"></a><a href="minitoring.php"><img src="images/button-3.jpg" width="170" height="54" border="0"></a><a href="showuser.php"><img src="images/button-4.jpg" width="170" height="52" border="0"></a><a href="loginout.php"><img src="images/button-5.jpg" width="170" height="55" border="0"></a><img src="images/mbtm.jpg" width="170" height="117" border="0">
</Td>
<Td width="788" valign="top">
<table border=0 cellspacing=1 align=center class=Navi>
  
</table>
<p><br>
</p>
<p ><center>
  <span class="STYLE1">您要修改的服务器信息如下</span>
</center></p>
<table width="477" align="center" cellspacing="0"  id="mytable" summary="The technical specifications of the Apple PowerMac G5 series"> 
<form action="modify.php" method="post">
<tr>
<th width="178">ip</th>
<th width="316"><input type="text"  name="ipinfo" value="<?php echo $menu["ip_address"];?>" readonly="true" /></th>
</tr>
<tr>
<th>status</th><th><input type="text"  name="statusinfo"  value="<?php echo $menu["status"];?>"/></th>
</tr>
<tr>
<th>cpu</th><th><input type="text"  name="cpuinfo"  value="<?php echo $menu["cpu"];?>"/></th>
</tr>
<tr>
<th>memory</th><th><input type="text"  name="memoryinfo"  value="<?php echo $menu["memory"];?>"/></th>
</tr>
<tr>
<th>disk</th><th><input type="text"  name="diskinfo"  value="<?php echo $menu["disk"];?>"/></th>
</tr>
<tr>
<th>userfilepath</th><th><input type="text"  name="userfilepath"  value="<?php echo $menu["userfilepath"];?>"/>
  (以“/”开头和结束manager不需填写)</th>
</tr>
<tr>
<th>Post_size(单位：M)</th><th><input type="text"  name="postsize"  value="<?php echo $menu["post_size"]/1000000;?>"/></th>
</tr>
<tr><td><input name="修改" type="submit"  value="修改"/></td><td><input type="button" name="back" value="返回" onClick="Returnback()"></td></tr>
</form>
</table>
<table border=0 cellpadding=0 cellspacing=0 align=center width='100%'>
  <tr>
    <td height=40></td>
  </tr>
  <tr>
    <td><hr size=1 color=#000000 width='60%' align=center></td>
  </tr>
  <tr>
    <td align=center>Copyright  &copy;  2010 , All Rights Reserved 
.</td>
  </tr>
  <tr>
    <td align=center><span class="STYLE1">E-mail: echo0104@126.com </span></td>
  </tr>
</table>

</Td>
</table>


    </td>
  </TR>
</table>
</BODY>
</html>