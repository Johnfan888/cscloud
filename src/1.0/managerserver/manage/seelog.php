 <?php
if(!$_COOKIE['admin']['user_id']){
	echo "δ��½";
	exit();
}
?>
<html>             
  <head> 
  <meta http-equiv="Content-Type" content="text/html; charset=gbk" />    
   <link rel='stylesheet' type='text/css' href='css/private.css'>
  </head>   

<body>
<table border="0" cellspacing="1" cellpadding="0" align="center" class=Navi>
<tr><td colspan="6"><h2>�鿴��־��Ϣ</h2> </td></tr>
<tr>
 <td width="10%">id</td>
 <td width="13%">ʱ��</td>
 <td width="19%">�ļ���</td>
 <td width="12%">����</td>
 <td width="8%">������</td>
 <td width="38%">����</td>
</tr>
<?php 
//�������ݿ�
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();
$pagesize=10; //����ÿһҳ��ʾ�ļ�¼��
$rs=mysql_query("select count(*) from log",$user->Con1); //ȡ�ü�¼����$rs
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
$rs=mysql_query("select * from log order by id desc limit $offset,$pagesize",$user->Con1);/*if ($myrow = mysql_fetch_array($rs))
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
		       <td>".$menu['id']."</td>   <td>".$menu['time']."</td><td>".$menu['filename']."</td><td>".$menu['operate']."</td><td>".$menu['operator']."</td><td>".$menu['prescribe']."</td>
			   </tr>";  
		  }
}
?>

��<?php
echo "</table>";
echo "<div align='center'>����".$pages."ҳ(".$page."/".$pages.")";
for ($i=1;$i<$page;$i++)
echo "<a href='seelog.php?page=".$i."'>[".$i ."]</a> ";
echo "[".$page."]";
for ($i=$page+1;$i<=$pages;$i++)
echo "<a href='seelog.php?page=".$i."'>[".$i ."]</a> ";
echo "</div>";
?>

</body>
</html>
