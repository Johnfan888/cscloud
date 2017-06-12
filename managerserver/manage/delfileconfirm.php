<?php 

$dir=$_GET['dir'];
$id=$_GET['id'];
$name=$_GET['name'];
//echo "dir =".$dir;
//echo "<br>id=".$id;
$serverip=$_GET['serverip'];
 //弹出文件删除确认对话框
				    echo "<script language=\"JavaScript\">";
					echo "if(confirm(\"当前删除的文件是".$name.",确定删除？\"))";
					echo " { location.href=\"deleteuserfile.php?dir=".$dir."&id=".$id." &serverip=".$serverip."\";}";
					echo " else { location.href=\"updown.php\";}";
					echo "</script>"; 
				 


?>