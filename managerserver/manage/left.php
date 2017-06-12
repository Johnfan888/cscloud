<?php
require("../smarty/Smarty.class.php");
//$tpl=new Smarty();
$comm = new Smarty();
$admin=$_COOKIE["administator"];
if($admin==1)
{
 $comm->display("manage/left.html");
}
else if($admin==0)
{
  $comm->display('manage/left_user.html');
}

?>