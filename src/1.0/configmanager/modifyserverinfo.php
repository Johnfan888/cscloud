<?php 
	session_start();
	header('Content-Type:text/html;charset=gb2312');
	if($_SESSION['name']=="")
	{
	echo "��δ��½��";
	exit();
	
	}
require("conn/conn.php");
$ip=$_GET["ip"];

$sql="select * from ip_table where ip_address='".$ip."'";
$result=mysql_query($sql,$conne->getconnect());
$menu=mysql_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>��ӷ�����</title>
  <link href="css/common.css" type="text/css" rel="stylesheet" />
  <link href="css/frame_main.css" type="text/css" rel="stylesheet" />
  <link href="css/page_list.css" type="text/css" rel="stylesheet" />
  <link href="css/page.css" type="text/css" rel="stylesheet" />
  <script language="javascript" type="text/javascript"> 
	function Returnback()
	{
		location.href='configserver.php'; 
	}
	</script>
	<script>
  	function monitor(){
		//window.open("../zabbix1/dashboard.php");
		window.open("/zabbix");
	}
  </script>
 </head>
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
							<form action="add.php" method="post">
								<table width="477" align="center" cellspacing="0"  id="mytable" summary="The technical specifications of the Apple PowerMac G5 series">
								<tr>
								<th width="178">ip</th>
								<th width="316"><input type="text"  name="ipinfo" value="<?php echo $menu["ip_address"];?>" readonly="true" /></th>
								</tr>
								<tr>
								<th>status</th><th><input type="text"  name="statusinfo"  value="<?php echo $menu["status"];?>"/></th>
								</tr>
								<tr>
								<th>cpu</th><th><input type="text"  name="cpuinfo"  value="<?php echo $menu["cpu"];?>"/></th>
								</tr>
								<tr>
								<th>memory</th><th><input type="text"  name="memoryinfo"  value="<?php echo $menu["memory"];?>"/></th>
								</tr>
								<tr>
								<th>disk</th><th><input type="text"  name="diskinfo"  value="<?php echo $menu["disk"];?>"/></th>
								</tr>
								<tr>
								<th>userfilepath</th><th><input type="text"  name="userfilepath"  value="<?php echo $menu["userfilepath"];?>"/>
								</th>
								</tr>
								<tr>
								<th>Post_size</th><th><input type="text"  name="postsize"  value="<?php echo $menu["post_size"]/1000000;?>"/></th>
								</tr>
								<tr>
								
								<td colspan="2"><input name="DT??" type="submit"  value="modify"/></td>
								<td colspan="2"><input type="button" name="back" value="back" onClick="Returnback()"></td>
								</tr>
								</table>

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
