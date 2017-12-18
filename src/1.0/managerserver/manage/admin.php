<?php
if(!$_COOKIE['admin']['user_id']){
	exit();
}
?>
<html>
<head>
<title>后台管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
</head>
<frameset cols="180,*" framespacing="0" border="0" frameborder="0">
  <frame name="menu" src="left.php" scrolling="yes">
  <frame name="main" src="main.php" scrolling="yes">
  <noframes>
  <body topmargin="0" leftmargin="0">
  <p>此网页使用了框架，但您的浏览器不支持框架</p>
  </body>
  </noframes>
</frameset>
</html>