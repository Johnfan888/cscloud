<?php 

$dir=$_GET['dir'];
$id=$_GET['id'];
$name=$_GET['name'];
//echo "dir =".$dir;
//echo "<br>id=".$id;
$serverip=$_GET['serverip'];
 //�����ļ�ɾ��ȷ�϶Ի���
				    echo "<script language=\"JavaScript\">";
					echo "if(confirm(\"��ǰɾ�����ļ���".$name.",ȷ��ɾ����\"))";
					echo " { location.href=\"deleteuserfile.php?dir=".$dir."&id=".$id." &serverip=".$serverip."\";}";
					echo " else { location.href=\"updown.php\";}";
					echo "</script>"; 
				 


?>