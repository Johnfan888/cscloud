<?php 
$ip=$_GET['ip'];
 //弹出文件删除确认对话框
				    echo "<script language=\"JavaScript\">";
					echo "if(confirm(\"当前删除的服务器是".$ip.",确定删除？\"))";
					echo " { location.href=\"deleteserverinfo.php?ip=".$ip."\";}";
					echo " else { location.href=\"configserver.php\";}";
					echo "</script>"; 
				 


?>