<html>             
  <head> 
  <meta http-equiv="Content-Type" content="text/html; charset=gbk" />    
    <link   href='style.css'   rel=stylesheet>             
    
  </head>             
  <body>    
<?PHP
function firm()
{//利用对话框返回的值 （true 或者 false）
    echo "<script language=\"JavaScript\">";
	echo "if(confirm(\"当前目录有更新，是否更新？\"))";
    echo " { location.href=\"dir.php\";}";
    echo " else { /*alert(\"你按了取消，那就是返回false\")*/;}";
	echo "</script>"; 
}
/*echo "<script language=\"JavaScript\">prompt(\"当前目录已有更新，是否刷新！\",location.href=\"index.php\");</script>"; */
/*echo "<script language=\"JavaScript\">confirm(\"当前目录已有更新，是否刷新！\",location.href=\"index.php\");</script>"; */
 firm();


?>
</body>