<?php   
header("cache-control:no-cache,must-revalidate");   
header("Content-Type:text/html;charset=gb2312");   
$showtime = date("����ʱ��Y��m��d��H:i:s");   
echo $showtime;  
 echo	"<table width=\"90%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=Navi>";
    echo "<tr><td width=\"20%\"><strong>�鿴�ں�/����ϵͳ/CPU��Ϣ</strong>�� </td>";
   echo "<td>";
   system('uname -a');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>�鿴�������</strong>�� </td>";
   echo "<td>";
   system('free -m');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>�鿴�ڴ�ʹ�����ͽ�����ʹ����</strong>�� </td>";
   echo "<td>";
   system('free -m');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>�鿴������ʹ�����</strong>�� </td>";
   echo "<td>";
   system('df -h');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>�鿴�ڴ�����</strong>�� </td>";
   echo "<td>";
   system('grep MemTotal /proc/meminfo');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>�鿴�����ڴ���</strong>�� </td>";
   echo "<td>";
   system('grep MemFree /proc/meminfo ');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>�鿴ϵͳ����ʱ�䡢�û���������</strong>�� </td>";
   echo "<td>";
   system('uptime');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>�鿴ϵͳ����</strong>�� </td>";
   echo "<td>";
   system('cat /proc/loadavg');
   echo "</td></tr>";
   echo "</table>";
?>  

  
  
