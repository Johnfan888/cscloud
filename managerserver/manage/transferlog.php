<html>             
  <head> 
  <meta http-equiv="Content-Type" content="text/html; charset=gbk" />    
   <link rel='stylesheet' type='text/css' href='css/private.css'>
  </head>   

<body>
<table border="0" cellspacing="1" cellpadding="0" align="center" class=Navi>
<tr><td colspan="6"><h2>�鿴�ļ�Ǩ����־��Ϣ</h2> </td></tr>
<tr>
 <td width="16%">�ļ�id���ļ�����</td>
 <td width="20%">Ǩ��ʱ��</td>
 <td width="12%">Դ�ļ�������ip</td>
 <td width="12%">Ŀ�������ip</td>
 <td width="20%">Դ·��</td>
 <td width="20%">��·��</td>
</tr>
<?php 
	require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();
$pagesize=10; //����ÿһҳ��ʾ�ļ�¼��
$rs=mysql_query("select * from log_transfer",$user->Con1); //ȡ�ü�¼����$rs
$myrow = mysql_fetch_array($rs);
$numrows=$myrow[0];
echo "numberrows:".$numrows;
//������ҳ��
$pages=intval($numrows/$pagesize);

//echo $pages;
if ($numrows%$pagesize)
$pages++;
//����ҳ��
if(isset($_GET['page']))
{
  $page=$_GET['page'];
}
else
{ 
  $page=1;
} 

$offset=$pagesize*($page - 1);
//��ȡָ����¼��
$rs=mysql_query("select * from log_transfer order by id desc limit $offset,$pagesize",$user->Con1);/*if ($myrow = mysql_fetch_array($rs))
{
$i=0;*/

?>
<?php
/* $sql="select * from log"; 
 $res=mysql_query($sql,$Con); */
 $numrows=mysql_num_rows($rs);
if($numrows>0)
{
 for($rows=0;$rows<$numrows;$rows++)             
		  {             
		  //����ǰ�˵���Ŀ�����ݵ�������             
		  $menu=mysql_fetch_array($rs);  
		  echo"<tr>
		       <td>".$menu['fileid']."</td>   <td>".$menu['time']."</td><td>".$menu['originip']."</td><td>".$menu['targetip']."</td><td>".$menu['originpath']."</td><td>".$menu['newpath']."</td>
			   </tr>";  
		  }
}
?>

��<?php
echo "</table>";
echo "<div align='center'>����".$pages."ҳ(".$page."/".$pages.")";
for ($i=1;$i<$page;$i++)
echo "<a href='transferlog.php?page=".$i."'>[".$i ."]</a> ";
echo "[".$page."]";
for ($i=$page+1;$i<=$pages;$i++)
echo "<a href='transferlog.php?page=".$i."'>[".$i ."]</a> ";
echo "</div>";
?>

</body>
</html>
