<?php 
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();

$newname=$_GET['newname'];
$id=$_GET['id'];
 
 echo "newname=". $newname;
 echo"<br>.id=".$id;
	//�޸�tb_file_all�е���Ϣ
	$sql="update tb_file_all set name='".$newname."'where id='".$id."'";
    mysql_query($sql,$user->Con1);
 // echo $sql;
	 //�޸�tb_file_cache�е���Ϣ,����Ҫ��tb_file_cache����û��������¼���еĻ��޸ģ�û�Ļ����޸ĺ�Ĳ���
	 $sql="select * from tb_file_cache  where id='".$id."'";
	 $re=mysql_query($sql,$user->Con1);
	// echo $sql;
	 $num=mysql_num_rows($re);
	 if($num>0)//˵����,�޸�
	 {
	  $sql="update tb_file_cache set name='".$newname."'where id='".$id."'";
    mysql_query($sql,$user->Con1);
		//echo $sql;
	 }
	 else
	 {//û�У���Ҫ���룬����ǰ�ȼ��ռ��Ƿ�
	      $sql="select * from tb_file_cache";
			$res=mysql_query($sql,$user->Con1);
			 $num=mysql_num_rows($res);
			if(($order_num-$num)>=1)//˵���ռ乻��
			{ 
				 $sql="insert into tb_file_cache (select * from tb_file_all where id='".$id."')";
				mysql_query($sql,$user->Con1);
			
			}
			else//��ɾ��1��
			{
				$sql="delete from tb_file_cache order by modifytime asc limit 1";
			  mysql_query($sql,$user->Con1);
			//  echo $sql;
				 $sql="insert into tb_file_cache (select * from tb_file_all where id='".$id."')";
				mysql_query($sql,$user->Con1);
				
			
			}

	 
	 }
	 
	 
    
 
 
//˵�����ļ�

header("Location:updown.php");



?>