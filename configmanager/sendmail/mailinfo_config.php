<?php
session_start();
if($_SESSION['name']==""){
	echo "��δ��¼";
	exit();
}
require("../conn/conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>�ʼ�����</title>
		<link type="text/css" rel="stylesheet" href="../css/common.css"/>
		<link type="text/css" rel="stylesheet" href="../css/frame_main.css"/>
		<link type="text/css" rel="stylesheet" href="../css/page.css"/>
		<link type="text/css" rel="stylesheet" href="../css/page_list.css"/>
	</head>
	<script>
		function monitor(){
			window.open("../../zabbix1/dashboard.php");
		}
	</script>
	<body>
	<!--header-->
		<div class="frame-header">
			<ul class="user-menu">
				<li class="">
					<a href="../loginout.php" class="menu-switch menu-arrow" id="js_page_client_tab"><span>�˳�</span></a>
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
								<a href="../configserver.php"><i class="ico-dm dm-document"></i>����������</a>
							</li>
							<li>
								<a href="../installserver.php"><i class="ico-dm dm-photo"></i>��������װ</a>
							</li>
							<li>
								<a href="../minitoring.php"><i class="ico-dm dm-music"></i>��ط�����</a>
							</li>
							<li>
								<a href="../minitoritem.php"><i class="ico-dm dm-music"></i>snmp�����</a>
							</li>
							<li>
								<a onclick="monitor()"><i class="ico-dm dm-music"></i>���ϵͳ</a>
							</li>
							<li>
								<a href="../showuser.php"><i class="ico-dm dm-video"></i>�û�����</a>
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
							<?php 
							require("../configure_class.php");
							$c = new Configuration();
							$c->configFile="/srv/www/htdocs/configmanager/config/mailinfo_config.txt";
							$c->_construct();
							?>
							<form method="post" action="mailinfo_config_change.php">
								<p>
								�ʼ���������
								<input type="text" name="server" value="<?php echo $c->_get("Server")?>"></input>
								</p>
								<p>
								�����˿ڣ�
								<input type="text" name="port" value="<?php echo $c->_get("Port")?>"></input>
								</p>
								<p>
								���ͷ������ַ��
								<input type="text" name="from" value="<?php echo $c->_get("From")?>"/>
								</p>
								<p>
								����:
								<input type="text" name="passwd" value="<?php echo $c->_get("Passwd")?>"/>
								</p>
								<p>
								���շ������ַ��
								<input type="text" name="to" value="<?php echo $c->_get("To")?>"/>
								</p>
								<p>
								<input type="submit" value="�޸�"/> 
								</p>
							</form>
							<?php 
								
							?>	
						</div>
					</div>

					<div class="page-footer"><div class="file-pages"></div></div>
			</div>
		</div>
	</div>
		</div>
	</body>
</html>