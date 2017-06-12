<?php /* Smarty version 2.6.14, created on 2012-04-29 14:11:30
         compiled from password.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title> <?php echo $this->_tpl_vars['title']; ?>
 </title>
  <link href="css/common.css" type="text/css" rel="stylesheet" />
  <link href="css/frame_main.css" type="text/css" rel="stylesheet" />
  <link href="css/page_list.css" type="text/css" rel="stylesheet" />
  <link href="css/page.css" type="text/css" rel="stylesheet" />
  <link type="text/css" rel="stylesheet" href="css/register.css" />
 </head>

 <body>
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "lib/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<div style="display: block; " id="js_frame_box">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "lib/left.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<div class="frame-contents" id="js_frame_box">

			<div class="page-contents">
				<div class="page-main" id="js_cantain_box">
					<div class="page-header">
						<div class="operate-panel" id="js_top_bar_box">
							<div class="opt-button">
							<em>修改密码</em>
							</div>
							<div class="opt-side"></div>
						</div>
						 <div class="directory-path">
						 </div>
					</div>

					<div class="page-list" id="js_data_list_outer">
						<div style="min-height:100%;_height:100%;cursor:default; background:#fff; margin:20px 50px;" id="js_data_list"> 
								<form vail="1" id="signForm" action="" method="post" autocomplete="off">
								<table>
								<tr>
								<td>旧密码：</td><td><input type="password" id="reg_password" name="passwd0" class="text" maxlength="18" /></td>
								</tr>
								<td>新密码：</td><td><input type="password" id="reg_password" name="passwd1" class="text" maxlength="18" /></td>
								<tr>
								</tr>
								<tr>
								<td>确认新密码：</td><td><input type="password" id="reg_passwordconfirm" name="passwd2" class="text" maxlength="18" /></td>
								</tr>
								<tr><td></td><td><button type="submit" name="submit">确认修改</button></td></tr>
								</table>
							</form>
							 <span style="color:red;"><?php echo $this->_tpl_vars['msg']; ?>
</span>
						</div>
					</div>

					<div class="page-footer">
						<div class="file-pages"></div>
					</div>
			</div>
		</div>
</body>
</html>