<?php /* Smarty version 2.6.14, created on 2012-06-10 04:01:23
         compiled from server.html */ ?>
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
				    <div class="directory-path"><a href="server.php?act=add">新增</a>
						<div class="list-filter" id="js_fileter_box">
							<div class="list-refresh">
								<a href="server.php">刷新</a>
							</div>
						</div>
					</div>
				 </div>

				<div class="page-list" id="js_data_list_outer">
				<div style="min-height:100%;_height:100%;cursor:default; background:#fff;" id="js_data_list">
				<table style="width:100%; text-align:center;">
					<tbody>
						<tr><th>服务器IP</th><th>文件存储路径</th><th>服务器状态</th></tr>	
						<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['server']):
?>
						<tr>
						<td><?php echo $this->_tpl_vars['server']['server_ip']; ?>
</td>
						<td><?php echo $this->_tpl_vars['server']['file_path']; ?>
</td>
						<td><?php echo $this->_tpl_vars['server']['status']; ?>
</td>
						</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table>
				
				<?php if ($this->_tpl_vars['act'] == 'add'): ?>
				<form name="form1" action="server.php?act=add" method="post">
				<table style="width:50%; text-align:left;">
				<tr><th>新增服务器</th><th></th></tr>
				<tr><td>服务器IP</td><td><input name="ip" type="text" value="<?php echo $this->_tpl_vars['ip']; ?>
"> (注意：写入后不可修改，请仔细填写)</td></tr>
				<tr><td>文件存储路径</td><td><input name="path" readonly="true" type="text" value="<?php echo $this->_tpl_vars['path']; ?>
"></td></tr>
				<tr><td></td><td><input class="button" type="submit" name="submit" value="增 加">&nbsp;&nbsp;<input class="button" type="button" value="取 消" onclick="javascript:window.location.href='server.php';"></td></tr>
				</table>
				</form>
				<?php endif; ?>

				<?php if ($this->_tpl_vars['act'] == 'edit'): ?>
				<form name="form1" action="server.php?act=edit" method="post">
				<table style="width:50%; text-align:left;">
				<tr><th>修改服务器</th><th></th></tr>
				<tr><td>服务器IP</td><td><input name="ip" type="text" value="<?php echo $this->_tpl_vars['ip']; ?>
"><input name="id" type="hidden" value="<?php echo $this->_tpl_vars['id']; ?>
"></td></tr>
				<tr><td>文件存储路径</td><td><input name="path" readonly="true" type="text" value="<?php echo $this->_tpl_vars['path']; ?>
"></td></tr>
				<tr><td>服务器状态</td><td><?php echo $this->_tpl_vars['status']; ?>
</td></tr>
				<tr><td></td><td><input class="button" type="submit" name="submit" value="修 改"></td></tr>
				</table>
				</form>
				<?php endif; ?>

				</div>
				</div>

				<div class="page-footer">
					<div class="file-pages"></div>
				</div>

			</div>
		</div>
	</div>
</div>
<?php echo $this->_tpl_vars['text']; ?>

</body>
</html>