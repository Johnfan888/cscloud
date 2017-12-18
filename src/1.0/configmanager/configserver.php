<?php
	session_start();
	if($_SESSION['name']=="")
	{
	echo "尚未登陆！";
	exit();
	
	}
	require("conn/conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>服务器配置</title>
  <link href="css/common.css" type="text/css" rel="stylesheet" />
  <link href="css/frame_main.css" type="text/css" rel="stylesheet" />
  <link href="css/page_list.css" type="text/css" rel="stylesheet" />
  <link href="css/page.css" type="text/css" rel="stylesheet" />
<script language="javascript" type="text/javascript"> 
function Add()
{
    location.href='addnewserver.php'; 
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
			<a href="loginout.php" class="menu-switch menu-arrow" id="js_page_client_tab"><span>退出</span></a>
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
						<a href="configserver.php"><i class="ico-dm dm-document"></i>服务器配置</a>
					</li>
					<li>
						<a href="installserver.php"><i class="ico-dm dm-photo"></i>服务器安装</a>
					</li>
					<li>
						<a href="minitoring.php"><i class="ico-dm dm-music"></i>监控服务器</a>
					</li>
					<li>
						<a href="minitoritem.php"><i class="ico-dm dm-music"></i>snmp监控项</a>
					</li>
					<li>
						<a  onclick="monitor()"><i class="ico-dm dm-music"></i>监控系统</a>
					</li>
					<li>
						<a href="showuser.php"><i class="ico-dm dm-video"></i>用户管理</a>
					</li>
					<li>
						<a href="loginout.php"><i class="ico-dm dm-sync"></i>退出系统</a>
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
							<table>
							<tr>
								<th colspan="7">服务器的基本信息</th>
							</tr>
							<tr>
							 <th width="20%"><span class="STYLE7">serverip</span></th>
							 <th width="20%"><span class="STYLE7">status</span></th>
							 <th width="20%"><span class="STYLE7">cpu</span></th>
							 <th width="15%"><span class="STYLE7">memory</span></th>
							 <th width="15%"><span class="STYLE7">disk</span></th>
							 <th width="20%"><span class="STYLE7">userfilepath</span></th>
							 <th width="20%"></th><th width="20%"></th>
							</tr>
							<?php
							$sql="select * from ip_table";
							$result=mysql_query($sql,$conne->getconnect());
							$nums=mysql_num_rows($result);

							for($rows=0;$rows<$nums;$rows++)
							{	$menu=mysql_fetch_array($result);
								 echo "<tr onmouseover=\"this.style.background='#ccccFF'; \" onmouseout =\"this.style.background=''; this.style.borderColor=''\" >"; 
								echo"<th >".$menu["ip_address"]."</th>";
								echo"<th>".$menu["status"]."</th>";
								echo"<th>".$menu["cpu"]."</th>";
								echo"<th>".$menu["memory"]."</th>";
								echo"<th>".$menu["disk"]."</th>";
								echo"<th>".$menu["userfilepath"]."</th>";
								echo"<th><a href='modifyserverinfo.php?ip=".$menu["ip_address"]."'>修改</a></th>";
								echo"<th><a href='deleteconfirm.php?ip=".$menu["ip_address"]."'>删除</a></th>";
								echo"</tr>";
							}
							?>
							<tr>
								<th><input type="button" name="add" value="添加新服务器" onClick="Add()"></th>
							</tr>
							</table>
						</div>
					</div>

					<div class="page-footer"><div class="file-pages"></div></div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
