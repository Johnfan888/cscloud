<?php
	session_start();
	header('Content-Type:text/html;charset=gb2312');
	if($_SESSION['name']=="")
	{
	echo "��δ��½��";
	exit();
	
	}
require("conn/conn.php");
?>
<html>             
  <head> 
  <meta http-equiv="Content-Type" content="text/html; charset=gbk" />    
   <link rel='stylesheet' type='text/css' href='css/private.css'>
   <STYLE type=text/css>
A:link { COLOR: blue; FONT-WEIGHT: none; TEXT-DECORATION: none }
A:visited { color: blue; font-weight: none; TEXT-DECORATION: none }
A:hover { color: red; font-weight: bold; text-decoration: underline }
</STYLE>
<script language="javascript">
function close_window()
{
	window.close();

}
</script>
</HEAD><BODY LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0" BGCOLOR="#FFFFFF">
<?php echo 'welcome  '.$_SESSION['name']; echo"   <a href=\"loginout.php\">�˳�ϵͳ</a>";?>
<TR>
    <Td width="967" valign="top"> 
      <table cellpadding="0" cellspacing="0" width="100%" height="108"><Td width="780" height="108" valign="top"><img src="images/log.png" width="100%" height="108"></Td></table>
  </head>   

<body>
<table width="80%" border="0" cellspacing="1" cellpadding="0" align="center" class=Navi>
<tr><td colspan="3"><h2>�鿴��־��Ϣ</h2> </td><td><input name="window_close" type="button" onClick="close_window()" value="�رմ���"></td></tr>
<tr>
 <td width="25%">id</td>
 <td width="25%">ʱ��</td>
 <td width="25%">source_ip</td>
 <td width="25%">target_ip</td>
</tr>
<?php 
//�������ݿ�

$pagesize=10; //����ÿһҳ��ʾ�ļ�¼��
$rs=mysql_query("select count(*) from log_transfer",$conne->getconnect()); //ȡ�ü�¼����$rs
$myrow = mysql_fetch_array($rs);
$numrows=$myrow[0];
//echo $numrows;
//������ҳ��
$pages=intval($numrows/$pagesize);


if ($numrows%$pagesize)
$pages++;
//����ҳ��
if (isset($_GET['page']))
{
  $page=$_GET['page'];
}
else
{ 
  $page=1;
} 

$offset=$pagesize*($page - 1);
//��ȡָ����¼��
$rs=mysql_query("select * from log_transfer order by id desc limit $offset,$pagesize",$conne->getconnect());/*if ($myrow = mysql_fetch_array($rs))
{
$i=0;*/

?>
<?php

 $numrows=mysql_num_rows($rs);
if($numrows>0)
{
 for($rows=0;$rows<$numrows;$rows++)             
		  {             
		  //����ǰ�˵���Ŀ�����ݵ�������             
		  $menu=mysql_fetch_array($rs);  
		  echo"<tr>
		       <td>".$menu['id']."</td>   <td>".$menu['time']."</td><td>".$menu['source_ip']."</td><td>".$menu['target_ip']."</td>
			   </tr>";  
		  }
}
?>

��<?php
echo "</table>";
echo "<div align='center'>����".$pages."ҳ(".$page."/".$pages.")";
for ($i=1;$i<$page;$i++)
echo "<a href='see_log.php?page=".$i."'>[".$i ."]</a> ";
echo "[".$page."]";
for ($i=$page+1;$i<=$pages;$i++)
echo "<a href='see_log.php?page=".$i."'>[".$i ."]</a> ";
echo "</div>";
?>
<table border=0 cellpadding=0 cellspacing=0 align=center width='100%'>
  <tr>
    <td height=40></td>
  </tr>
  <tr>
    <td><hr size=1 color=#000000 width='60%' align=center></td>
  </tr>
  <tr>

.</td>
  </tr>
  <tr>
  </tr>
</table>
</body>
</html>
