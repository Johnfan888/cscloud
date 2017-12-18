
<?php
/*require("../include/comment.php");
$comm = new comment();*/
require("../smarty/Smarty.class.php");
//$tpl=new Smarty();
$comm = new Smarty();
$comm->display('manage/index.html');
?>