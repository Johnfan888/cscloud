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
        $string=fgets($fp);   //���ļ��ṹ��ָ��stream�ж�ȡ����
		fclose($fp);   //�ر��ļ�
        $arr=explode(" ",$string);
        $used=round($arr[1]/($arr[1]+$arr[2]),4); //��ȡ��ǰ�ĸ���ֵ
       */
	      //�ɼ�������Ϣ  usedisk
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
		{//˵��loading�б仯�������
		
			$sql="update ip_table set loading='".$loading."',transfer_status='0' where ip_address='".$menu["ip_address"]."'";
			mysql_query($sql,$conne->getconnect());
			//�޸�ip_table��transfer_status �ֶ��ø��ļ����������¼��뵽��ѡ�ı�Ǩ�Ʒ�����������
		}
}
?>

