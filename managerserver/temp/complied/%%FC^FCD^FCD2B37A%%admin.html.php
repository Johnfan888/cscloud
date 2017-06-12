<?php /* Smarty version 2.6.14, created on 2012-06-10 04:00:52
         compiled from admin.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'admin.html', 56, false),)), $this); ?>
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
								<a href="admin.php">刷新</a>
							</div>
						</div>
					</div>
				 </div>

				<div class="page-list" id="js_data_list_outer">
				<div style="min-height:100%;_height:100%;cursor:default; background:#fff;" id="js_data_list">
				<table style="width:100%; text-align:center;">
					<tbody>
						<tr><th>用户ID</th><th>账号</th><th>是否为管理员</th><th>账户状态</th><th>操作</th></tr>	
						<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['user']):
?>
						<tr>
							<td><?php echo $this->_tpl_vars['user']['user_id']; ?>
</td>
							<td><?php echo $this->_tpl_vars['user']['email']; ?>
</td>
							<td><?php echo $this->_tpl_vars['user']['is_admin']; ?>
</td>
							<td><?php echo $this->_tpl_vars['user']['is_checked']; ?>
</td>
							<td>
								<a href="admin.php?act=edit&id=<?php echo $this->_tpl_vars['user']['user_id']; ?>
">修改</a>
							</td>
						</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table>
				<?php if ($this->_tpl_vars['edit'] == 'true'): ?>
				<form name="form1" action="admin.php" method="post">
				<table style="width:50%; text-align:left;">
				<tr><th>修改用户信息</th><th></th><th></th></tr>
				<tr><td>账号</td><td><?php echo $this->_tpl_vars['email']; ?>
<input name="id" type="hidden" value="<?php echo $this->_tpl_vars['id']; ?>
"></td></tr>
				<tr><td>是否为管理员</td><td><?php echo smarty_function_html_radios(array('name' => 'admin','options' => $this->_tpl_vars['admin'],'checked' => $this->_tpl_vars['adminflag'],'separator' => "</td><td>"), $this);?>
</td></tr>
				<tr><td>账户状态</td><td><?php echo smarty_function_html_radios(array('name' => 'checked','options' => $this->_tpl_vars['checked'],'checked' => $this->_tpl_vars['checkedflag'],'separator' => "</td><td>"), $this);?>
</td></tr>
				<tr><td></td><td><input class="button" type="submit" name="submit" value="修 改"></td><td><input class="button" type="button" value="取 消" onclick="javascript:window.location.href='admin.php';"></td></tr>
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