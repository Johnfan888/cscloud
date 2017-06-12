 <?php
if(!$_COOKIE['admin']['user_id']){
	echo "未登陆";
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>无标题文档</title>
<link rel='stylesheet' type='text/css' href='css/private.css'>
</head>

<body>
<?php 
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();
$deletedirname=$_POST['deletedirname'];
$parent_id=$_GET['parent_id'];
/*$deletedirname="znn";
$parent_id=0;*/
//利用父目录的id号和要删除的文件的文件名在数据库中定位要删除的记录，将其删除
$p_id=$parent_id;//把父节点的id值保存下来
echo $deletedirname."<br>";
echo $parent_id."<br>";
static $name;
static $dirpath;

while(1)
{
		   if($parent_id==0)
			{  
			     echo "->".$parent_id;
			     $dirpath=$rootdir.$name.$deletedirname; 
				 echo "<br> path is:".$dirpath."<br>";
				 
				 if(is_dir($dirpath))
				 {      
				        
						 //弹出文件删除确认对话框
				    echo "<script language=\"JavaScript\">";
					echo "if(confirm(\"当前删除的目录是".$dirpath.",确定删除？\"))";
					echo " { location.href=\"deletedir.php?parent_id=".$p_id."& deletedirname=".$deletedirname." &dirpath=".$dirpath."\";}";
					echo " else { location.href=\"dir.php\";}";
					echo "</script>"; 
				 
				 }
				 else
				 {
						echo "<script language=\"JavaScript\">";
						echo "if(confirm(\"当前目录不存在，请返回！\"))";
						echo " { location.href=\"dir.php\";}";
						echo " else { location.href=\"dir.php\";}";
						echo "</script>"; 
				        exit();
				 }
				 break;
			}
			else
			{	 
				 $sql="select * from menu where id='".$parent_id."'";
				 $res=mysql_query($sql,$user->Con1);
				 $menu=mysql_fetch_array($res);
				 $name=$menu['name']."/".$name;
				 echo "->".$parent_id;
				 $parent_id=$menu['parent_id'];
			}
}




?>
</body>
</html>
