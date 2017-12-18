<?php
	if(isset($_GET["act"]))
	{
	if($_GET["act"] == "do") {
       	$newname=$_POST["newname"];
		 $id=$_GET['id'];	
			header("Location:renamefromdb.php?newname=".$newname."&id=".$id);
			exit;
		}
	}
?>
<html >
 <head> 
  <meta http-equiv="Content-Type" content="text/html; charset=gbk" />    
   <link rel='stylesheet' type='text/css' href='css/private.css'>           
  <script   language="JavaScript"   src="TreeMenu.js"></script>
<style type="text/css">
<!--
.STYLE1 {
	font-size: 24px;
	font-weight: bold;
	color: #0033CC;
}
-->
</style>
</head>  
   <body>
   <div></div>
	 <div><center><h2>文件改名</h2></center></div>
	  
<form action="rename.php?act=do&id=<?php echo $_GET['id']; ?>" method="post">
<table border="0" cellspacing="0" cellpadding="0" align="center" class=Navi>
<tr><td>
  <strong>修改文件名</strong><br />  将 <strong><?php echo $_GET["name"]; ?></strong> 改名为：<input type="text" name="newname" size="25" />
</td></tr> 
<tr><td>
	<input type="submit" value="修改" />
	<input type="button" value="取消" onClick="history.back();" />
</td></tr>
</table>
</form>

   </body>
</html>