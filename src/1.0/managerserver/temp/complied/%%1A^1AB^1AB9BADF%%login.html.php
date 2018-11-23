<?php /* Smarty version 2.6.14, created on 2018-11-23 21:16:19
         compiled from login.html */ ?>
﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="css/login.css" />
<title><?php echo $this->_tpl_vars['title']; ?>
 </title>
</head>
<body>
<div class="screen-wrap" id="js_scroll_warp">
	<div class="screen-container screen-01">
		<div class="header">
			<h1 class="logo"></h1>
			<div class="honor-text"></div>
		</div>
		<div class="more-link">
		<h1 style="color:#FFF;float:right; margin:10px 5px 0 0;">Copyright © 2014 信息存储技术研究室(ISTL)</h1>
		</div>
		<div class="screen-box">
			<div class="login-box">
				<div class="top-reg">
				<a href="register.php" class="button btn-reg" title="注册">立即注册</a>
				</div>
				<form id="js_login_form" action="login.php" name="form1" method="post" onSubmit="return checkinfo()">
					<dl class="login-form">
						<dt><strong>帐号</strong></dt>
						<dd>
							<input type="text" name="username" id="account" class="text" value="<?php echo $this->_tpl_vars['name']; ?>
" />
						</dd>
						<dt><strong>密码</strong></dt>
						<dd>
							<input type="password" name="password" id="passwd" class="text" />
						</dd>
						<dd class="login-bottom">
							<button type="submit" class="button btn-login" name="submit"><i>登录</i></button>
						</dd>
					</dl>
				</form>
				<dl class="access-box"><dt><div style="text-align:center; color:red;"><?php echo $this->_tpl_vars['error']; ?>
</div></dt></dl>
			</div>
			<div class="client-link"></div>
			<div class="video-box"></div>
		</div>
	</div>
<script type="text/javascript">
function checkinfo()
{
	if (form1.username.value==""){
		alert("请输入用户名！");
		document.form1.username.focus();
		return false;
	}
	if (form1.password.value==""){
		alert("请输入密码！");
		document.form1.password.focus();
		return false;
	}
}
</script>
</body>
</html>