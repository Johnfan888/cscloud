<?php 
$name=$_GET['name'];
$dir=$_GET['dir'];
$id=$_GET['id'];
$serverip=$_GET['serverip'];
 //弹出文件删除确认对话框
				    echo "<script language=\"JavaScript\">";
					echo "if(confirm(\"当前删除的目录是".$name.",确定删除？\"))";
					echo " { location.href=\"deleteuserdir.php?dir=".$dir."& id=".$id." &serverip=".$serverip."\";}";
					echo " else { location.href=\"updown.php\";}";
					echo "</script>"; 
				 


?>