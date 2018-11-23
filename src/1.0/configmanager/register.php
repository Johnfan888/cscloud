<?php
	session_start();
	header('Content-Type:text/html;charset=gb2312');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>注册</title>
<link rel="stylesheet" href="css/style.css" />
<script language="javascript" src="js/xmlhttprequest.js"></script>
<script language="javascript" src="js/register.js"></script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:315px;
	top:266px;
	width:349px;
	height:25px;
	z-index:1;
}
-->
</style>
</head>
<body>
<div id="container">
<div id="rgbgdiv">
  <div id="regnamediv"><b>注册名称：</b><input id="regname" name="regname" type="text" /><div id="namediv">请输入用户名</div>
	</div>
	<div id="regpwddiv1"><b>注册密码：</b><input id="regpwd1" name="regpwd1" type="password" /><div id="pwddiv1">请输入密码</div>
	</div>
	<div id="regpwddiv2"><b>确认密码：</b><input id="regpwd2" name="regpwd2" type="password" /><div id="pwddiv2">请输入确认密码</div>
  </div>
	
	<div id="regemaildiv"><b>电子邮箱：</b><input id="email" name="email" type="text" />
	<div id="emaildiv">请输入用户的邮箱地址</div>
	</div>
	
	<div id="regemaildiv">&nbsp;
	  <div id="emaldiv">**为了在忘记密码时能找回新密码，请填写详细信息中的密保问题和密保答案</div></div>
	
	
	
	<div id="morediv" style="display:none;">
    <hr id="part" />
	<div id="regquestiondiv"><b>密保问题：</b><input id="question" name="question" type="text" /><div id="questiondiv">用户找回密码使用</div></div>
	<div id="reganswerdiv"><b>密保答案：</b><input id="answer" name="answer" type="text" /><div id="answerdiv">用户找回密码使用</div></div>
	<div id="regrealnamediv"><b>真实姓名：</b><input id="realname" name="realname" type="text" /><div id="realnamediv">用户的真实姓名</div></div>
	<div id="regbirthdaydiv"><b>出生日期：</b><input id="birthday" name="birthday" type="text" /><div id="birthdaydiv">用户的出生日期。格式：YYYY-MM-DD</div></div>
	<div id="regtelephonediv"><b>联系电话：</b><input id="telephone" name="telephone" type="text" /><div id="telephonediv">用户的联系电话</div></div>
	<div id="regqqdiv"><b>QQ号 码：</b><input id="qq" name="qq" type="text" /><div id="qqdiv">用户QQ号</div></div>
  </div>
  	<div id="btndiv2">
  		<button id="regbtn" disabled="disabled">&nbsp;</button>
		<button id="morebtn">&nbsp;</button>
		<button id="logbtn">&nbsp;</button>
	</div>
    <div id="imgdiv" style=" visibility: hidden;">&nbsp;</div>
</div>
</body>
</html>
