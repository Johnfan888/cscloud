<?php /* Smarty version 2.6.14, created on 2018-11-23 21:18:36
         compiled from backup.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title><?php echo $this->_tpl_vars['title']; ?>
 </title>
  <link href="../css/common.css" type="text/css" rel="stylesheet" />
  <link href="../css/frame_main.css" type="text/css" rel="stylesheet" />
  <link href="../css/page_list.css" type="text/css" rel="stylesheet" />
  <link href="../css/page.css" type="text/css" rel="stylesheet" />
  <script type="text/javascript" src="../js/jquery.js"></script>
  <script type="text/javascript" src="../js/util.js"></script>
 </head>
<body>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "lib/admin_header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div style="display: block; " id="js_frame_box">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "lib/admin_left.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div class="frame-contents" id="js_frame_box">
		<div class="page-contents">
			<div class="page-main" id="js_cantain_box">
				<div class="page-header">
					<div class="operate-panel" id="js_top_bar_box">
						
						<div class="opt-side"></div>
					</div>
				    <div class="directory-path">
						<div class="list-filter" id="js_fileter_box">
							<div class="list-refresh">
								<a href="backup.php">刷新</a>
							</div>
						</div>
					</div>
				 </div>

				<div class="page-list" id="js_data_list_outer">
				<form method="post" action="backup.php">
					<div style="min-height:100%;_height:100%;cursor:default; background:#fff;" id="js_data_list">
					<table style="width:100%; text-align:center;">
					<tbody>	
						<tr>
		<td>
		密码设置
		</td>
		<td>
		<input name="pass" type="text" value="<?php echo $this->_tpl_vars['pwd']; ?>
"/>
		</td>
		<td>
		(如果需要设置密码，填入您的密码；如果不需要设置密码，则系统会自动生成密码)
		</td>
		</tr>
		<tr>
		<td>
			是否需要双备份 
		</td>
		<td>
		  <input name="Need_backup" type="text" value="<?php echo $this->_tpl_vars['double']; ?>
" />
		  </td>
		  <td>
		  (“0”表示需要，“1”表示不需要)
		  </td>
		</td>
		</tr>
		<tr>
		<td>
			用户文件双备份时间间隔 
		</td>
		<td>
		<input name="Time_interval" type="text" value="<?php echo $this->_tpl_vars['timespan']; ?>
" />
		<td>
		(单位：分钟)
		</td>
		</tr>
		<tr>
		<td></td>
		<td>
		<input class="button" type="submit" name="submit" value="配 置">
		</td>
	    <td></td>
		</tr>
					</tbody>
				</table>
					</div>
				</form>
				</div>

				<div class="page-footer">
					<div class="file-pages"></div>
				</div>

			</div>
		</div>
	</div>
</div>
</body>
</html>