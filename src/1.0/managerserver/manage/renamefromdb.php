<?php 
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();

$newname=$_GET['newname'];
$id=$_GET['id'];
 
 echo "newname=". $newname;
 echo"<br>.id=".$id;
	//修改tb_file_all中的信息
	$sql="update tb_file_all set name='".$newname."'where id='".$id."'";
    mysql_query($sql,$user->Con1);
 // echo $sql;
	 //修改tb_file_cache中的信息,现需要看tb_file_cache中有没有这条记录，有的话修改，没的话将修改后的插入
	 $sql="select * from tb_file_cache  where id='".$id."'";
	 $re=mysql_query($sql,$user->Con1);
	// echo $sql;
	 $num=mysql_num_rows($re);
	 if($num>0)//说明有,修改
	 {
	  $sql="update tb_file_cache set name='".$newname."'where id='".$id."'";
    mysql_query($sql,$user->Con1);
		//echo $sql;
	 }
	 else
	 {//没有，需要插入，插入前先检查空间是否够
	      $sql="select * from tb_file_cache";
			$res=mysql_query($sql,$user->Con1);
			 $num=mysql_num_rows($res);
			if(($order_num-$num)>=1)//说明空间够了
			{ 
				 $sql="insert into tb_file_cache (select * from tb_file_all where id='".$id."')";
				mysql_query($sql,$user->Con1);
			
			}
			else//先删除1条
			{
				$sql="delete from tb_file_cache order by modifytime asc limit 1";
			  mysql_query($sql,$user->Con1);
			//  echo $sql;
				 $sql="insert into tb_file_cache (select * from tb_file_all where id='".$id."')";
				mysql_query($sql,$user->Con1);
				
			
			}

	 
	 }
	 
	 
    
 
 
//说明是文件

header("Location:updown.php");



?>