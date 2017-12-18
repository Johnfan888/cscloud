<?php

$id=$_POST['id'];
modifyflag($id);

function modifyflag($id)
{
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();
$sql="update tb_file_location set flag='1' where id='".$id."'";
mysql_query($sql,$user->Con1);
}

?>