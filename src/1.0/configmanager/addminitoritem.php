<?php
	session_start();
	if($_SESSION['name']=="")
	{
	echo "��δ��½��";
	exit();
	}
require("conn/conn.php");
		$serverip=$_SERVER['SERVER_ADDR'];
		$sql1="select Max(mi_mib) as mi_mib from MinitorItem";
		$query=mysql_query($sql1,$conne->getconnect());
		
		while($menu=mysql_fetch_array($query)){
			if($menu['mi_mib'] == null)	{
				$id=50;
			}
			else{
				$array=explode(".",$menu['mi_mib']);
				$id=$array[8];
				$id=intval($id);
				$id=$id+1;
			}	
		}
		$mibnode=".1.3.6.1.4.1.1111.".$id;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>��Ӽ����</title>
  <link href="css/common.css" type="text/css" rel="stylesheet" />
  <link href="css/frame_main.css" type="text/css" rel="stylesheet" />
  <link href="css/page_list.css" type="text/css" rel="stylesheet" />
  <link href="css/page.css" type="text/css" rel="stylesheet" />
</head>
<script>
		function IsNull(){
			var mibvalue=document.getElementById('mib').value;
			if(mibvalue == ""){
				alert("mib�ڵ�ֵ����Ϊ�գ�");
				return false;
			}
			var name=document.getElementById('mibname').value;
			if(name == ""){
				alert("��������Ʋ���Ϊ�գ�");
				return false;
			}
			var shell=document.getElementById('shell').value;
			if(shell == ""){
				alert("�ű�����Ϊ�գ�");
				return false;
			}
			if(!document.getElementById('selfdefine').checked && !document.getElementById('all').checked){
				alert("��ѡ��ip���巽ʽ");
			}
			else{
				    if(confirm("��ӳɹ������޸�����,ȷ��������ݣ�")){
						document.form1.submit();
				    }
				}
		}

	 function IpRange(){
		 if(document.getElementById('selfdefine').checked){
		 document.getElementById("IpRange").style.display="";
		 }
		 else{
			
			 document.getElementById("IpRange").style.display="none";
		 }
	 }	
</script>
	<body>
	
				<!--header-->
<div class="frame-header">
	<ul class="user-menu">
		<li class="">
			<a href="loginout.php" class="menu-switch menu-arrow" id="js_page_client_tab"><span>�˳�</span></a>
		</li>
	</ul>
</div>
<!--content-->
<div style="display: block; " id="js_frame_box">
<!--left-->
<div class="frame-side" id="js_side_main_box">
	<div class="directory-menu" id="js_left_side_box">
		<dl id="js_tree_box">
			<dd>
				<ul>
					<li>
						<a href="configserver.php"><i class="ico-dm dm-document"></i>����������</a>
					</li>
					<li>
						<a href="installserver.php"><i class="ico-dm dm-photo"></i>��������װ</a>
					</li>
					<li>
						<a href="minitoring.php"><i class="ico-dm dm-music"></i>��ط�����</a>
					</li>
					<li>
						<a href="minitoritem.php"><i class="ico-dm dm-music"></i>snmp�����</a>
					</li>
					<li>
						<a onclick="monitor()"><i class="ico-dm dm-music"></i>���ϵͳ</a>
					</li>
					<li>
						<a href="showuser.php"><i class="ico-dm dm-video"></i>�û�����</a>
					</li>
					<li>
						<a href="loginout.php"><i class="ico-dm dm-sync"></i>�˳�ϵͳ</a>
					</li>
				</ul>
			</dd>
		</dl>
	</div>
</div>
<!--right-->
	<div class="frame-contents" id="js_frame_box">
		<div class="page-contents">
			<div class="page-main" id="js_cantain_box">
				<div class="page-header">
					<div class="operate-panel" id="js_top_bar_box">
						<div class="opt-button">
						
						</div>
					<div class="opt-side">
				</div>
			</div>
						<div class="directory-path">
							<div class="path-contents" rel="page_local">
							</div>
						<div class="list-filter" id="js_fileter_box"><div class="list-refresh"></div></div>
					</div>
				  </div>

					<div class="page-list" id="js_data_list_outer">
						<div style="min-height:100%;_height:100%;cursor:default; background:#fff;" id="js_data_list">
		<form action="" method="post" name="form1" id="form1" enctype="multipart/form-data">
		<table  width="80%" border="0"    cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td align="center" colspan="2"><span style="font-size:20px">���snmp�����</span></td>
			</tr>
			<p>			</p>
			<tr>
				<td width="30%" bgcolor="#FFFFFF" align="center">MIB�ڵ㣺</td>
				<td  width="70%" bgcolor="#FFFFFF">
				<input name="mib" type="text" id="mib" value="<?php echo $mibnode?>" style="width:200px" disabled="disabled">
				</input>
				</td>
			</tr>
			<tr>			</tr>
			<tr>
				<td width="30%" bgcolor="#FFFFFF" align="center">��������ƣ�</td>
				<td  width="70%" bgcolor="#FFFFFF"><input name="mibname" type="text" id="mibname"  value="<?php echo $name?>" style="width:200px
				"></input></td>
			</tr>
			<tr>			</tr>
			<tr>
				<td width="30%" bgcolor="#FFFFFF" align="center">ִ�нű���</td>
				<td  width="70%" bgcolor="#FFFFFF"><input name="shell" type="file" id="shell">
				<span style="color:#FF0000">����.sh�����Ľű���</span>
					</input>
				</td>
			</tr>
			<tr>
				<td width="30%" bgcolor="#FFFFFF" align="center">IP ���巽ʽ��</td>
				<td width="70%" bgcolor="#FFFFFF">
				ȫ�����ݷ�������<input type="radio" name="iprange" id="all" onClick="IpRange()" value="all" <?php if ($_POST['iprange']=='all') echo 'checked'; ?> ></input>&nbsp;&nbsp;&nbsp;&nbsp;
				�Զ���IP��Χ:<input type="radio" name="iprange" id="selfdefine" onClick="IpRange()" value="selfdefine" <?php if($_POST['iprange']=='selfdefine') echo 'checked';?>></input>				</td>
			</tr>
			<tbody id="IpRange" style="display:<?php if ($_POST['iprange']=='all' || $_POST['iprange']=='') echo 'none';else echo "";?>">
			<tr>
				<td width="30%" bgcolor="#FFFFFF" align="center">
					IP ��Χ��				</td>
				<td width="70%" bgcolor="#FFFFFF">
					<input type="text" name="selfiprange" id="selfiprange" value="<?php print $_POST['selfiprange'] ?>" style="width:200px">
					&nbsp;
					<span style="color:#FF0000">(��ʽ��192.168.1.1,2)</span>
						</input>				
					</td>
			</tr>	
			</tbody>
			<tr>
				<td width="30%" bgcolor="#FFFFFF" align="center">��ؽڵ�������</td>
				<td  width="70%" bgcolor="#FFFFFF"><textarea name="desc" id="desc" value="<?php print $desc?>" cols="1" rows="1"></textarea></td>
			</tr>
			
			<tr>
				<td align="center"><input  type="button" name="add" id="add" value="��Ӽ����" onclick="return IsNull()"/></td>
			</tr>
		</table >
		</form>
		</div>
					</div>

					<div class="page-footer"><div class="file-pages"></div></div>
			</div>
		</div>
	</div>
</div>
	</body>
</html>
<?php
	if(isset($_POST['mibname'])){
		//$mibnode=$_POST['mib'];
		$name=$_POST['mibname'];
		$iprangetype=$_POST['iprange'];
		$iprange=$_POST['selfiprange'];
		if($iprangetype=="all"){
			$iprange="ALL DATA SERVER";
		}
		else{
			$iprange=$_POST['selfiprange'];	
		}
		$desc=$_POST['desc'];
		$shellname=$_FILES['shell']['name'];
		$shellpath="/srv/www/htdocs/configmanager/ms_minitoritem_scripts/".$shellname;
		$date=date('Y-m-d H:i:s');
	
		//�ж��ļ�����
		$type=strstr($shellname,".");
		if($iprangetype != "all" && !preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}(,[0-9]{1,3})*$/", $iprange))
		{
			echo "<script>alert('ip��ַ��ʽ����ȷ');</script>";
		}
		else{
			if($type!=".sh"){
				echo "<script>alert('�ϴ����ļ����Ͳ���ȷ');</script>";
			}
			else{
				if(move_uploaded_file($_FILES['shell']['tmp_name'], $shellpath)){
					$sql="INSERT INTO MinitorItem VALUES('','$mibnode','$name','$shellname','$shellpath','$date','$iprange','$desc')";
					$result=mysql_query($sql,$conne->getconnect());
					if($result){
						$SCRIPTS_DIR="/srv/www/htdocs/configmanager/ms_scripts";
						$DPASS="111111";
						if($iprangetype=="all"){
							$sql2="select ip_address from ip_table where status='file'";
							$query2=mysql_query($sql2,$conne->getconnect());
							while($result2=mysql_fetch_array($query2)){	
							$DIP=$result2['ip_address'];
							$cmd="/bin/sh ".$SCRIPTS_DIR."/Install_MonitorItem.sh ".$DIP." ".$DPASS." ".$mibnode." ".$name." ".$shellname." ".$serverip;
							exec($cmd,$output,$res);	
							//system($cmd);
							}
						}
						 else{
							$arrayip=explode(",",$iprange);  
							$countip=count($arrayip);
							$baseip=explode(".",$arrayip[0]);
							$cmd="/bin/sh ".$SCRIPTS_DIR."/Install_MonitorItem.sh ".$arrayip[0]." ".$DPASS." ".$mibnode." ".$name." ".$shellname." ".$serverip;
							exec($cmd,$output,$res);
							//echo $arrayip[0];
							$baseip=$baseip[0].".".$baseip[1].".".$baseip[2].".";
							for($i=1;$i<$countip;$i++){
							$DIP=$baseip.$arrayip[$i];
							//echo $DIP;
							$cmd="/bin/sh ".$SCRIPTS_DIR."/Install_MonitorItem.sh ".$DIP." ".$DPASS." ".$mibnode." ".$name." ".$shellname." ".$serverip;
							exec($cmd,$output,$res);
							}
							 }
						echo "<script>alert('���snmp��ؽڵ�ɹ�');location.href('minitoritem.php');</script>";
					}
					else{
						echo "<script>alert('���snmp��ؽڵ�ʧ��');</script>";
					}
				}
				else{
					echo "<script>alert('�ϴ���ؽű�ʧ��');</script>";
				}
			}
		
		}
	}
?>
