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
$sql="select * from tb_file_all where parent_id='".$id."'";//���ﲻ��дfileowner�ˣ���Ϊ��parent_id�Ѿ������ж���
$result=mysql_query($sql,$userr->Con1);
$num=mysql_num_rows($result);
//echo $num;
if($num>0)//˵�����������ļ���Ŀ¼��������ɾ��Ŀ¼
{
 /*echo "This dir is not empty! Can't delete!";
 echo"<a href='updown.php'>����</a>";*/
  echo "<script language=JavaScript>";
					echo "if(confirm('��ǰĿ¼�ǿգ�����ɾ��'))";
					echo " { location.href='updown.php';}";
					echo " else {location.href='updown.php';}";
					echo "</script>"; 
				
}
else if($num==0)//˵����Ŀ¼Ϊ�գ�����ɾ����Ŀ¼
{

//�����ݿ����ҵ����ļ��Ĵ������̨�ļ��������ϣ�Ȼ���ļ�����Ϣ�ݽ���ȥ				 
 header("location:http://".$serverip."/www/index.php?flag=delete&username=".$user."&dir=".$dir."&id=".$id);
}

?>