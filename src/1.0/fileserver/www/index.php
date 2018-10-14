<?php   
ob_start();
require("conn.php");
$username=$_GET["username"];
setcookie("username",$username,time()+3600,"/");
$parent_id=$_GET['parent_id'];

setcookie("login","yes");
//创建目录结束
if($_GET['flag']=='upload')
{
 // echo $_GET["dirpath"]; 
   header("location:demo1.php?parent_id=".rawurlencode($parent_id)."&dirpath=".$_GET["dirpath"]."&owner=".$username."&replicaip=".$_GET['replicaip']."&replicapath=".$_GET['replicapath']."&manageserverip=".$_GET["manageserverip"]."&totalsize=".$_GET["totalsize"]."&usedsize=".$_GET["usedsize"]."&userfilepath=".$_GET["userfilepath"]);
}
else if($_GET['flag']=='delete')
{
  header("location:del.php?dir=".rawurlencode($_GET["dir"])."&id=". rawurlencode($_GET['id'])."&owner=".$username); 
}
/*else if($_GET['flag']=='rename')
{
  header("location:rename.php?dir=".rawurlencode($dir)."&file=". rawurlencode($file)."&owner=".$username); 
}*/
else if($_GET['flag']=='newdir')
{
   header("location:fs_newdir.php?dir=".rawurlencode($_GET["dir"])."&parent_id=".rawurlencode($parent_id)."&dirpath=".$_GET["dirpath"]."&owner=".$username."&replicaip=".$_GET['replicaip']."&replicapath=".$_GET['replicapath']);
}
?>
