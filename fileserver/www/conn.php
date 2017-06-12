<?php 
require("configure_class.php");
$c = new Configuration();
$c->_construct();
$serverid=$c->_get("ServerID");
$webserverip=$c->_get("ManagerServerIP");
$userPath = $c->_get("UserFilePath");
?>