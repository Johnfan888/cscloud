<?php
		require("conn/conn.php");
        require("conn/config.php");
        $sql="select ip_address from ip_table where status='file'";
        $res=mysql_query($sql,$conne->getconnect());
        $nums=mysql_num_rows($res);
		
for($i=0;$i<$nums;$i++)
{
        $menu=mysql_fetch_array($res);
        $DESCIP=$menu["ip_address"];
       /* $filename=$mrtgpath.$menu["ip_address"]."_disk.old";
        //echo $filename;
				
        $fp=fopen($filename,"r");
        $string=fgets($fp);   //从文件结构体指针stream中读取数据
		fclose($fp);   //关闭文件
        $arr=explode(" ",$string);
        $used=round($arr[1]/($arr[1]+$arr[2]),4); //读取以前的负载值
       */
	      //采集负载信息  usedisk
          $cmd="/usr/bin/snmpdf -v 1 -c public ".$DESCIP." | grep /data | awk '{print $3}'"; //used
	      $used=system($cmd);
	      $cmd="/usr/bin/snmpdf -v 1 -c public ".$DESCIP." | grep /data | awk '{print $4}'"; //used
	      $avail=system($cmd);
	      $loading=round($used/($used+$avail),4);
	      
		$sql="select loading from ip_table where ip_address='".$menu["ip_address"]."'";
	    $result=mysql_query($sql,$conne->getconnect());
		$array=mysql_fetch_array($result);
		$oldloading=$array["loading"];
		if($loading!=$oldloading)
		{//说明loading有变化，则更新
		
			$sql="update ip_table set loading='".$loading."',transfer_status='0' where ip_address='".$menu["ip_address"]."'";
			mysql_query($sql,$conne->getconnect());
			//修改ip_table的transfer_status 字段让该文件服务器重新加入到待选的被迁移服务器队列中
		}
}
?>

