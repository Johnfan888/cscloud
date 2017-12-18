<?php   
header("cache-control:no-cache,must-revalidate");   
header("Content-Type:text/html;charset=gb2312");   
$showtime = date("北京时间Y年m月d日H:i:s");   
echo $showtime;  
 echo	"<table width=\"90%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=Navi>";
    echo "<tr><td width=\"20%\"><strong>查看内核/操作系统/CPU信息</strong>： </td>";
   echo "<td>";
   system('uname -a');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>查看计算机名</strong>： </td>";
   echo "<td>";
   system('free -m');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>查看内存使用量和交换区使用量</strong>： </td>";
   echo "<td>";
   system('free -m');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>查看各分区使用情况</strong>： </td>";
   echo "<td>";
   system('df -h');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>查看内存总量</strong>： </td>";
   echo "<td>";
   system('grep MemTotal /proc/meminfo');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>查看空闲内存量</strong>： </td>";
   echo "<td>";
   system('grep MemFree /proc/meminfo ');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>查看系统运行时间、用户数、负载</strong>： </td>";
   echo "<td>";
   system('uptime');
   echo "</td></tr>";
   
   echo "<tr><td width=\"20%\"><strong>查看系统负载</strong>： </td>";
   echo "<td>";
   system('cat /proc/loadavg');
   echo "</td></tr>";
   echo "</table>";
?>  

  
  
