<html>             
  <head> 
  <meta http-equiv="Content-Type" content="text/html; charset=gbk" />    
    <link   href='style.css'   rel=stylesheet>             
    
  </head>             
  <body>    
<?PHP
function firm()
{//���öԻ��򷵻ص�ֵ ��true ���� false��
    echo "<script language=\"JavaScript\">";
	echo "if(confirm(\"��ǰĿ¼�и��£��Ƿ���£�\"))";
    echo " { location.href=\"dir.php\";}";
    echo " else { /*alert(\"�㰴��ȡ�����Ǿ��Ƿ���false\")*/;}";
	echo "</script>"; 
}
/*echo "<script language=\"JavaScript\">prompt(\"��ǰĿ¼���и��£��Ƿ�ˢ�£�\",location.href=\"index.php\");</script>"; */
/*echo "<script language=\"JavaScript\">confirm(\"��ǰĿ¼���и��£��Ƿ�ˢ�£�\",location.href=\"index.php\");</script>"; */
 firm();


?>
</body>