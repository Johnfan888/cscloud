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
$deletefilename=$_POST['deletefilename'];
$parent_id=$_GET['parent_id'];
/*$deletedirname="znn";
$parent_id=0;*/
//利用父目录的id号和要删除的文件的文件名在数据库中定位要删除的记录，将其删除
$p_id=$parent_id;//把父节点的id值保存下来
echo $deletefilename."<br>";
echo $parent_id."<br>";
static $name;

//找到文件的绝对路径然后再将它删除
while(1)
{
		   if($parent_id==0)
			{  
			     echo "->".$parent_id;
			     $filepath=$rootdir.$name.$deletefilename; 
				 echo "<br> path is:".$filepath."<br>";
				   
				   //弹出文件删除确认对话框
				    echo "<script language=\"JavaScript\">";
					echo "if(confirm(\"当前删除的文件所在的目录是".$filepath.",确定删除文件\"))";
					echo " { location.href=\"deletefile.php?parent_id=".$p_id."& deletefilename=".$deletefilename." &filepath=".$filepath."\";}";
					echo " else { location.href=\"dir.php\";}";
					echo "</script>"; 
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
