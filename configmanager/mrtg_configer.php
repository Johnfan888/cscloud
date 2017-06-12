<?php 
//新建mrtg目录
require("conn/conn.php");
$dir="/srv/www/htdocs/mrtg";
mkdir($dir,0700);
$cmd="/usr/bin/sudo /usr/bin/cfgmaker";


$cmd=$cmd.">/srv/www/htdocs/mrtg/mrtg.cfg";
//system($cmd);

$filename=$dir."/mrtg.cfg";
$fp=fopen($filename,"w");
$string="# Created by 
# /usr/local/mrtg-2/bin/cfgmaker public@192.168.1.15 public@192.168.1.16


### Global Config Options

#  for UNIX
# WorkDir: /home/http/mrtg

#  or for NT
# WorkDir: c:\mrtgdata

### Global Defaults

#  to get bits instead of bytes and graphs growing to the right
# Options[_]: growright, bits

EnableIPv6: no
WorkDir: /srv/www/htdocs/mrtg
Language:chinese
Options[_]: growright, bits
######################################################################
# System: cscvm3
# Description: Linux cscvm3 2.6.16.60-0.21-xenpae #1 SMP Tue May 6 12:41:02 UTC 2008 i686
# Contact: Sysadmin (root@localhost)
# Location: Server Room
######################################################################


";
fwrite($fp,$string);


$sql="select * from ip_table where status='file'";
$result=mysql_query($sql,$conne->getconnect());
$nums=mysql_num_rows($result);
for($i=0;$i<$nums;$i++)
{
//修改配置文件
	$menu=mysql_fetch_array($result);
	$cmd=$cmd." public@".trim($menu["ip_address"]);
	
	$string="Target[".trim($menu["ip_address"])."_mem]:`snmpwalk -v 1  ".trim($menu["ip_address"])." -c public .1.3.6.1.4.1.1111.53 | grep 53.101 | awk -F"."\\"."\" '{print $2}'` 
#Targey[".trim($menu["ip_address"])."_mem]: memTotalReal.0&memAvailReal.0:holdata@holdata.3322.org 
Xsize[".trim($menu["ip_address"])."_mem]: 500 
Ysize[".trim($menu["ip_address"])."_mem]: 300
Ytics[".trim($menu["ip_address"])."_mem]: 7 
MaxBytes[".trim($menu["ip_address"])."_mem]: 512 
Title[".trim($menu["ip_address"])."_mem]:Memory State of WY1 IP ".trim($menu["ip_address"])."Server 
PageTop[".trim($menu["ip_address"])."_mem]:<H1>Memory State of WY1 IP ".trim($menu["ip_address"])."Server</H1>; 
ShortLegend[".trim($menu["ip_address"])."_mem]: 
kmg[".trim($menu["ip_address"])."_mem]: MB
kilo[".trim($menu["ip_address"])."_mem]:1024 
YLegend[".trim($menu["ip_address"])."_mem]: Memory Usage 
Legend1[".trim($menu["ip_address"])."_mem]: 已用内存 
Legend2[".trim($menu["ip_address"])."_mem]: 总内存  
Legend3[".trim($menu["ip_address"])."_mem]: 已用内存 
Legend4[".trim($menu["ip_address"])."_mem]: 总内存 
LegendI[".trim($menu["ip_address"])."_mem]: 已用内存 
LegendO[".trim($menu["ip_address"])."_mem]: 总内存
Options[".trim($menu["ip_address"])."_mem]: growright,gauge,nopercent


Target[".trim($menu["ip_address"])."_disk]:`snmpwalk -v 1  ".trim($menu["ip_address"])." -c public .1.3.6.1.4.1.1111.55 | grep 55.101 | awk -F"."\\"."\" '{print $2}'` 
#Targey[".trim($menu["ip_address"])."_disk]: memTotalReal.0&memAvailReal.0:holdata@holdata.3322.org 
Xsize[".trim($menu["ip_address"])."_disk]: 500 
Ysize[".trim($menu["ip_address"])."_disk]: 300
Ytics[".trim($menu["ip_address"])."_disk]: 30 
MaxBytes[".trim($menu["ip_address"])."_disk]: 21000
Title[".trim($menu["ip_address"])."_disk]:Disk State of WY1 IP ".trim($menu["ip_address"])."Server 
PageTop[".trim($menu["ip_address"])."_disk]:<H1>Disk State of WY1 IP ".trim($menu["ip_address"])."Server</H1>; 
ShortLegend[".trim($menu["ip_address"])."_disk]: 
kmg[".trim($menu["ip_address"])."_disk]: MB
kilo[".trim($menu["ip_address"])."_disk]:1024 
YLegend[".trim($menu["ip_address"])."_disk]: Disk Usage 
Legend1[".trim($menu["ip_address"])."_disk]: 已用空间 
Legend2[".trim($menu["ip_address"])."_disk]: 总空间 
Legend3[".trim($menu["ip_address"])."_disk]: 已用空间  
Legend4[".trim($menu["ip_address"])."_disk]: 总空间   
LegendI[".trim($menu["ip_address"])."_disk]: 已用空间  
LegendO[".trim($menu["ip_address"])."_disk]: 总空间 
Options[".trim($menu["ip_address"])."_disk]: growright,gauge,nopercent

";
fwrite($fp,$string);
}
fclose($fp);

//生成index.html
$cmd="/usr/bin/sudo /usr/bin/indexmaker --output=/srv/www/htdocs/mrtg/index.html --title=monitor /srv/www/htdocs/mrtg/mrtg.cfg";
system($cmd);

$cmd="/usr/bin/sudo /usr/bin/mrtg /srv/www/htdocs/mrtg/mrtg.cfg";
system($cmd);
system($cmd);
system($cmd);

//修改mrtg目录的权限
$cmd="/usr/bin/sudo /bin/chown wwwrun.www /srv/www/htdocs/mrtg -R";
system($cmd);
?>