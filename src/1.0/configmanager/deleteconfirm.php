<?php 
$ip=$_GET['ip'];
 //�����ļ�ɾ��ȷ�϶Ի���
				    echo "<script language=\"JavaScript\">";
					echo "if(confirm(\"��ǰɾ���ķ�������".$ip.",ȷ��ɾ����\"))";
					echo " { location.href=\"deleteserverinfo.php?ip=".$ip."\";}";
					echo " else { location.href=\"configserver.php\";}";
					echo "</script>"; 
				 


?>