<?php
	session_start();
	header('Content-Type:text/html;charset=gb2312');
	if($_SESSION['name']=="")
	{
	echo "��δ��½��";
	exit();
	}
require("conn/conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>���������</title>
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
							<?php require("configure_class.php");
							$c = new Configuration();
							$c->configFile="/srv/www/htdocs/configmanager/config/config.txt";
							$c->_construct();?>
							<form method="post" action="monitor_method_change.php">
								<p>
									<!--��ǰ�ļ��Ǩ�Ʒ�ʽ("0"Ϊ�Զ���"1"Ϊ�ֶ�)-->
									<input type="text" name="monitoring_method" value="<?php echo $c->_get("Method") ?>" style="display:none"/>
								</p>
								<p>
									Ǩ�Ƹ�����ֵ(��ʽ��0.01)
									<input type="text" name="loading_threshold" value="<?php echo $c->_get("Loading_threshold") ?>"/>
								</p>
								<p>
									<!--Ǩ�Ʋ�ѯ���ʱ��(���ӣ���ʽ��5)-->
									<input type="text" name="inquire_time" value="<?php echo $c->_get("Inquire_time") ?>" style="display:none"/>
								</p>
								<p>
								</p>
									ϵͳ�������ֵ(��ʽ��0.01)
									<input type="text" name="maxloading_threshold" value="<?php echo $c->_get("MaxLoading_threshold") ?>"/>
								<p>
									<input type="submit" value="�޸�">
								</p>
								  
							</form>
								<p>
								  <input type="button" onClick="openNew()" value="��ʼ��ظ���Ǩ��"> 
								  <input type="button" onClick="watchGragh()" value="�鿴���ļ��������ĸ���ͼ" style="display:none"> 
								</p>
								<p> 
									<input type="button" onClick="seeLog()" value="�鿴Ǩ����־"> 
									<input type="button" onClick="mail()" value="�����ʼ���Ϣ"></input>
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
