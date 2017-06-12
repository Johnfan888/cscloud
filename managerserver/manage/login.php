<?php
require("../include/comment.php");
require("../include/user.class.php");
$username=$_POST['username'];
$password=$_POST['password'];

$user =&username::getInstance();
$user->login($username,$password);

?>