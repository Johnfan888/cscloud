<?php /* Smarty version 2.6.14, created on 2012-06-10 04:01:51
         compiled from zone.html */ ?>
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
								<a href="zone.php">刷新</a>
							</div>
						</div>
					</div>
				 </div>

				<div class="page-list" id="js_data_list_outer">
				<div style="min-height:100%;_height:100%;cursor:default; background:#fff;" id="js_data_list">
				<table style="width:100%; text-align:center;">
					<tbody>
						<tr><th>用户账号</th><th>主文件服务器</th><th>副本文件服务器</th><th>总空间</th><th>已使用空间</th><th>操作</th></tr>	
						<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['zone']):
?>
						<tr>
						<td><?php echo $this->_tpl_vars['zone']['email']; ?>
</td>
						<td><?php echo $this->_tpl_vars['zone']['server_ip']; ?>
</td>
						<td><?php echo $this->_tpl_vars['zone']['ha_server_ip']; ?>
</td>
						<td><?php echo $this->_tpl_vars['zone']['useable_size']; ?>
</td>
						<td><?php echo $this->_tpl_vars['zone']['used_size']; ?>
</td>
						<td>
								<a href="zone.php?act=edit&id=<?php echo $this->_tpl_vars['zone']['user_id']; ?>
">修改</a>
							</td>
						</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table>

				<?php if ($this->_tpl_vars['act'] == 'edit'): ?>
				<form name="form1" action="zone.php?act=edit" method="post">
				<table style="width:50%; text-align:left;">
				<tr><th>用户账号</th><th><?php echo $this->_tpl_vars['email']; ?>
<input name="id" type="hidden" value="<?php echo $this->_tpl_vars['id']; ?>
"></th></tr>
				<tr><td>主文件服务器IP</td><td><input readonly="true" type="text" value="<?php echo $this->_tpl_vars['ip']; ?>
"></td></tr>
				<tr><td>副本文件服务器IP</td><td><input readonly="true" type="text" value="<?php echo $this->_tpl_vars['ha_ip']; ?>
"></td></tr>
				<tr><td>总空间</td><td><input name="size" type="text" value="<?php echo $this->_tpl_vars['useable']; ?>
"></td></tr>
				<tr><td>已使用空间</td><td><?php echo $this->_tpl_vars['used']; ?>
</td></tr>
				<tr><td></td><td><input class="button" type="submit" name="submit" value="修 改">&nbsp;&nbsp;<input class="button" type="button" value="取 消" onclick="javascript:window.location.href='zone.php';"></td></tr>
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