<?php 
$name=$_GET['name'];
$dir=$_GET['dir'];
$id=$_GET['id'];
$serverip=$_GET['serverip'];
 //�����ļ�ɾ��ȷ�϶Ի���
				    echo "<script language=\"JavaScript\">";
					echo "if(confirm(\"��ǰɾ����Ŀ¼��".$name.",ȷ��ɾ����\"))";
					echo " { location.href=\"deleteuserdir.php?dir=".$dir."& id=".$id." &serverip=".$serverip."\";}";
					echo " else { location.href=\"updown.php\";}";
					echo "</script>"; 
				 


?>