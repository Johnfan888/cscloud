 <?php
if(!$_COOKIE['admin']['user_id']){
	echo "δ��½";
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�ޱ����ĵ�</title>
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
//���ø�Ŀ¼��id�ź�Ҫɾ�����ļ����ļ��������ݿ��ж�λҪɾ���ļ�¼������ɾ��
$p_id=$parent_id;//�Ѹ��ڵ��idֵ��������
echo $deletefilename."<br>";
echo $parent_id."<br>";
static $name;

//�ҵ��ļ��ľ���·��Ȼ���ٽ���ɾ��
while(1)
{
		   if($parent_id==0)
			{  
			     echo "->".$parent_id;
			     $filepath=$rootdir.$name.$deletefilename; 
				 echo "<br> path is:".$filepath."<br>";
				   
				   //�����ļ�ɾ��ȷ�϶Ի���
				    echo "<script language=\"JavaScript\">";
					echo "if(confirm(\"��ǰɾ�����ļ����ڵ�Ŀ¼��".$filepath.",ȷ��ɾ���ļ�\"))";
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
