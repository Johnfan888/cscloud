<?php

require("../include/comment.php");
require("../include/user.class.php");

$filename = $_POST['filename'];

$user = &username::getInstance();

$sql = "update adm_file_location set flag='1' where filename='".$filename."'";
mysql_query($sql,$user->Con1);

?>

