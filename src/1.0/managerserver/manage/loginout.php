<?php
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();
$user->login_out();
$user->cancleconnoction();

echo "<script language=\"JavaScript\">";
echo "location.href=\"index.php\";";
echo "</script>";
?>