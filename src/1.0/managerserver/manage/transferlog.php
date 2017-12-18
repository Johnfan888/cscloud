<html>             
  <head> 
  <meta http-equiv="Content-Type" content="text/html; charset=gbk" />    
   <link rel='stylesheet' type='text/css' href='css/private.css'>
  </head>   

<body>
<table border="0" cellspacing="1" cellpadding="0" align="center" class=Navi>
<tr><td colspan="6"><h2>查看文件迁移日志信息</h2> </td></tr>
<tr>
 <td width="16%">文件id（文件名）</td>
 <td width="20%">迁移时间</td>
 <td width="12%">源文件服务器ip</td>
 <td width="12%">目标服务器ip</td>
 <td width="20%">源路径</td>
 <td width="20%">新路径</td>
</tr>
<?php 
	require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();
$pagesize=10; //设置每一页显示的记录数
$rs=mysql_query("select * from log_transfer",$user->Con1); //取得记录总数$rs
$myrow = mysql_fetch_array($rs);
$numrows=$myrow[0];
echo "numberrows:".$numrows;
//计算总页数
$pages=intval($numrows/$pagesize);

//echo $pages;
if ($numrows%$pagesize)
$pages++;
//设置页数
if(isset($_GET['page']))
{
  $page=$_GET['page'];
}
else
{ 
  $page=1;
} 

$offset=$pagesize*($page - 1);
//读取指定记录数
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
		  //将当前菜单项目的内容导入数组             
		  $menu=mysql_fetch_array($rs);  
		  echo"<tr>
		       <td>".$menu['fileid']."</td>   <td>".$menu['time']."</td><td>".$menu['originip']."</td><td>".$menu['targetip']."</td><td>".$menu['originpath']."</td><td>".$menu['newpath']."</td>
			   </tr>";  
		  }
}
?>

　<?php
echo "</table>";
echo "<div align='center'>共有".$pages."页(".$page."/".$pages.")";
for ($i=1;$i<$page;$i++)
echo "<a href='transferlog.php?page=".$i."'>[".$i ."]</a> ";
echo "[".$page."]";
for ($i=$page+1;$i<=$pages;$i++)
echo "<a href='transferlog.php?page=".$i."'>[".$i ."]</a> ";
echo "</div>";
?>

</body>
</html>
