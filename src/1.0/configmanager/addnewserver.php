<?php 
	session_start();
	if($_SESSION['name']=="")
	{
	echo "��δ��½��";
	exit();
	
	}
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
							<table align="center" cellspacing="0"  id="mytable" >
								<tr>
								<th>����������</th><th></th>
								</tr>
								<tr>
								<th>IP</th><th><input name="ip" type="text"></th>
								</tr>
								<tr>
								<th>STATUS</th><th>
								  <input name="status" type="text" > 
								  (file/manager)
								  </th>
								</tr>
								<tr>
								<th>CPU</th><th><input name="cpu" type="text" ></th>
								</tr>
								<tr>
								<th>MEMORY</th><th><input name="memory" type="text" ></th>
								</tr>
								<tr>
								<th>DISK</th><th><input name="disk" type="text" ></th>
								</tr>
								<th>USREFILEPATH</th>
								<th><input name="userfilepath" type="text" >
								(�ԡ�/����ͷ�ͽ���manager������д)</th>
								</tr>
								<th>POST_SIZE(��λ��M)</th>
								<th><input name="postsize" type="text" >
								(������Ĭ��Ϊ1G=1000M)</th>
								</tr>
								<tr>
								<th colspan="2"><input  type="submit" name="���" value="���"/></th>
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
