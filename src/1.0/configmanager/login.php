<?php
        session_start();header('Content-Type:text/html;charset=gb2312');
        header('Content-Type:text/html;charset=gb2312');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�û���¼</title>
<script language="javascript" src="js/login.js"></script>
<script language="javascript" src="js/xmlhttprequest.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div id="container">
  <div id="bgdiv">
    <div id="txtdiv1"><input id="lgname" name="lgname" type="text" class="txt"/></div>
	<div id="txtdiv2"><input id="lgpwd" name="lgpwd" type="password" class="txt"/></div>
	<div id="txtdiv3"><input id="lgchk" type="text" maxlength="4" style="width:37px;"><img id='chkid' src=""/><a id="changea">Change</a></div>
	<div id="btndiv" style="text-align:center">
		<button id="lgbtn">&nbsp;</button>
		<button id="rgbtn">&nbsp;</button>
		<button id="fdbtn">&nbsp;</button>
		<input id="chknm" name="chknm" type="hidden" value=""  />
	</div>
	<div id="regimg" style=" visibility: hidden;">&nbsp;</div>
  </div>
</div>
</body>
</html>
