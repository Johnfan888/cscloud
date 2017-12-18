<?php 
$dir=$_GET['dir'];
$id=$_GET['id'];
$serverip=$_GET['serverip'];
$user=$_COOKIE['admin']['username'];
/*echo $dir;
echo $file;
echo $serverip;
echo $user;*/
require("../include/comment.php");
require("../include/user.class.php");

$userr =&username::getInstance();
$sql="select * from tb_file_all where parent_id='".$id."'";//这里不用写fileowner了，因为从parent_id已经可以判断了
$result=mysql_query($sql,$userr->Con1);
$num=mysql_num_rows($result);
//echo $num;
if($num>0)//说明有其他的文件或目录，则不允许删除目录
{
 /*echo "This dir is not empty! Can't delete!";
 echo"<a href='updown.php'>返回</a>";*/
  echo "<script language=JavaScript>";
					echo "if(confirm('当前目录非空，不能删除'))";
					echo " { location.href='updown.php';}";
					echo " else {location.href='updown.php';}";
					echo "</script>"; 
				
}
else if($num==0)//说明该目录为空，允许删除该目录
{

//在数据库中找到该文件的存放在那台文件服务器上，然后将文件的信息递交过去				 
 header("location:http://".$serverip."/www/index.php?flag=delete&username=".$user."&dir=".$dir."&id=".$id);
}

?>