<?php
session_start();
if($_SESSION['name']==""){
	echo "尚未登录";
	exit();
}
require("../conn/conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>邮件配置</title>
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
					<a href="../loginout.php" class="menu-switch menu-arrow" id="js_page_client_tab"><span>退出</span></a>
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
								<a href="../configserver.php"><i class="ico-dm dm-document"></i>服务器配置</a>
							</li>
							<li>
								<a href="../installserver.php"><i class="ico-dm dm-photo"></i>服务器安装</a>
							</li>
							<li>
								<a href="../minitoring.php"><i class="ico-dm dm-music"></i>监控服务器</a>
							</li>
							<li>
								<a href="../minitoritem.php"><i class="ico-dm dm-music"></i>snmp监控项</a>
							</li>
							<li>
								<a onclick="monitor()"><i class="ico-dm dm-music"></i>监控系统</a>
							</li>
							<li>
								<a href="../showuser.php"><i class="ico-dm dm-video"></i>用户管理</a>
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
							<?php 
							require("../configure_class.php");
							$c = new Configuration();
							$c->configFile="/srv/www/htdocs/configmanager/config/mailinfo_config.txt";
							$c->_construct();
							?>
							<form method="post" action="mailinfo_config_change.php">
								<p>
								邮件服务器：
								<input type="text" name="server" value="<?php echo $c->_get("Server")?>"></input>
								</p>
								<p>
								监听端口：
								<input type="text" name="port" value="<?php echo $c->_get("Port")?>"></input>
								</p>
								<p>
								发送方邮箱地址：
								<input type="text" name="from" value="<?php echo $c->_get("From")?>"/>
								</p>
								<p>
								密码:
								<input type="text" name="passwd" value="<?php echo $c->_get("Passwd")?>"/>
								</p>
								<p>
								接收方邮箱地址：
								<input type="text" name="to" value="<?php echo $c->_get("To")?>"/>
								</p>
								<p>
								<input type="submit" value="修改"/> 
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