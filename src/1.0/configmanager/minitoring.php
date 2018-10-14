<?php
	session_start();
	header('Content-Type:text/html;charset=gb2312');
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
  <title>服务器监控</title>
  <link href="css/common.css" type="text/css" rel="stylesheet" />
  <link href="css/frame_main.css" type="text/css" rel="stylesheet" />
  <link href="css/page_list.css" type="text/css" rel="stylesheet" />
  <link href="css/page.css" type="text/css" rel="stylesheet" />  
  <script language="javascript" type="text/javascript"> 
	function watchGragh()
	{
		window.location.href="../mrtg/index.html";
	}  
   function openNew()
   {
	   newWin=window.open('if_need_transfer.php?flag=1');
   }
   
   function seeLog()
   {
		newWin=window.open("see_log.php");
   }
   function monitor(){
		//window.open("../zabbix1/dashboard.php");
		window.open("http://192.168.1.225:8000/main/main");

	}
   function mail(){
	   	window.location.href="./sendmail/mailinfo_config.php";
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
						<a onclick="monitor()"><i class="ico-dm dm-music"></i>监控系统</a>
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
							<?php require("configure_class.php");
							$c = new Configuration();
							$c->configFile="/srv/www/htdocs/configmanager/config/config.txt";
							$c->_construct();?>
							<form method="post" action="monitor_method_change.php">
								<p>
									<!--当前的监控迁移方式("0"为自动，"1"为手动)-->
									<input type="text" name="monitoring_method" value="<?php echo $c->_get("Method") ?>" style="display:none"/>
								</p>
								<p>
									迁移负载阈值(格式：0.01)
									<input type="text" name="loading_threshold" value="<?php echo $c->_get("Loading_threshold") ?>"/>
								</p>
								<p>
									<!--迁移查询间隔时间(分钟，格式：5)-->
									<input type="text" name="inquire_time" value="<?php echo $c->_get("Inquire_time") ?>" style="display:none"/>
								</p>
								<p>
								</p>
									系统最大负载阈值(格式：0.01)
									<input type="text" name="maxloading_threshold" value="<?php echo $c->_get("MaxLoading_threshold") ?>"/>
								<p>
									<input type="submit" value="修改">
								</p>
								  
							</form>
								<p>
								  <input type="button" onClick="openNew()" value="开始监控负载迁移"> 
								  <input type="button" onClick="watchGragh()" value="查看各文件服务器的负载图" style="display:none"> 
								</p>
								<p> 
									<input type="button" onClick="seeLog()" value="查看迁移日志"> 
									<input type="button" onClick="mail()" value="配置邮件信息"></input>
								</p>
								
								
						</div>
					</div>

					<div class="page-footer"><div class="file-pages"></div></div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
