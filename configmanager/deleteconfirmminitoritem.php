<?php
$mi_mib=$_GET['mi_mib'];
					echo "<script language=\"JavaScript\">";
					echo "if(confirm('ȷ��ɾ����ǰ��¼'))";
					echo " { location.href=\"deleteminitoritem.php?mi_mib=".$mi_mib."\";}";
					echo " else { location.href='minitoritem.php';}";
					echo "</script>";
?> 
