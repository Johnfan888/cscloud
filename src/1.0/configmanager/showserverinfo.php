<?php
	session_start();
	header('Content-Type:text/html;charset=gb2312');
	if($_SESSION['name']=="")
	{
	echo "尚未登陆！";
	exit();
	
	}
	require("conn/conn.php");
$ip=$_POST['ip'];
?>
<HTML><HEAD><TITLE>查看各服务器的配置信息</TITLE>
<META name="description" content="">
<META name="keywords" content="">
<STYLE type=text/css>
A:link { COLOR: blue; FONT-WEIGHT: none; TEXT-DECORATION: none }
A:visited { color: blue; font-weight: none; TEXT-DECORATION: none }
A:hover { color: red; font-weight: bold; text-decoration: underline }
</STYLE>

<script language="javascript"> 
var xmlHttp; 
function createXMLHttpRequest(){ 
if(window.ActiveXObject){ 
xmlHttp = new ActiveXObject("microsoft.XMLHTTP"); 
} 
else if(window.XMLHttpRequest){ 
xmlHttp = new XMLHttpRequest(); 
} 
else{ 
alert("创建请求失败"); 
} 
} 

function sendRequest(){ 
createXMLHttpRequest(); 
var f=document.frm
var ip=f.ip.value;
url = "http://"+ip+"/www/gettime.php"; 
xmlHttp.onreadystatechange = callback; 
xmlHttp.open('GET',url,true); 
xmlHttp.send(null);
} 

function callback(){ 
if(xmlHttp.readyState ==4){ 
if(xmlHttp.status == 200){ 
document.getElementById("time").innerHTML = xmlHttp.responseText; 
setTimeout("sendRequest()",1000); 
} 
} 
} 


function getInfo()
   {
         var f=document.frm
	     var ip=f.ip.value;
	    alert(ip);
   }
</script> 
 
</HEAD><BODY LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0" BGCOLOR="#FFFFFF">
<?php echo '欢迎光临！'.$_SESSION['name']; echo"   <a href=\"loginout.php\">退出系统</a>";?>
<TR>
    <Td width="967" valign="top"> 
      <table cellpadding="0" cellspacing="0" width="100%" height="108"><Td width="780" height="108" valign="top"><img src="images/topbar.jpg" width="100%" height="108"></Td></table>

<table cellpadding="0" cellspacing="0" width="960">
<Td width="170" valign="top">
<img src="images/menutop.jpg" width="170" height="45"><a href="configserver.php"><img src="images/button-1.jpg" width="170" height="49" border="0"></a><a href="installserver.php"><img src="images/button-2.jpg" width="170" height="52" border="0"></a><a href="minitoring.php"><img src="images/button-3.jpg" width="170" height="54" border="0"></a><a href="showuser.php"><img src="images/button-4.jpg" width="170" height="52" border="0"></a><a href="loginout.php"><img src="images/button-5.jpg" width="170" height="55" border="0"></a><img src="images/mbtm.jpg" width="170" height="117" border="0">
</Td>
<Td width="788" valign="top">
<table width="100%" >
<tr><td width="24%">
  <table  border=0 cellspacing=1 align=center class=List style="border-left: 1 solid #000000; border-right: 1 solid #000000; border-top: 1 solid #000000; border-bottom: 1 solid #000000" width="90%">
  <tr>
    <th>查看集群系统的ip地址列表</th>
  </tr>
  <tr>
    <th>IP_address</th>
  </tr>
  <?php 
  $sql="select * from ip_table";
   $result=mysql_query($sql,$conne->getconnect());
    $num=mysql_num_rows($result);
for($rows=0;$rows<$num;$rows++)
{
  $menu=mysql_fetch_array($result);  
   echo   "<tr onmouseover=\"this.style.background='#ccc'; \" onmouseout =\"this.style.background=''; this.style.borderColor=''\" >";    
   echo "<th>".$menu["ip_address"]."</th>";
   echo"</tr>";
   }
  ?>
</table>
</td>
<td width="76%">
    <table width="80%">
	<tr><td colspan="2"><form name="frm">输入想要查看的服务器的ip地址<input name="ip"  id="ip_address" type="text"></form>
	      <input type="button" value="确定" onClick="sendRequest();" /> 
	</td></tr>
</table>
<!--<input type="button" value="check it" onClick="sendRequest();" /> -->
<br/> 
<span id="time"></span> 
</body>   
</td>
</table>
<br>
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
    <td align=center>E-mail: echo0104@126.com </td>
  </tr>
</table>

</Td>
</table>


    </td>
  </TR>
</table>
</BODY>
</HTML>
