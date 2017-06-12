<?php
/*require("../include/comment.php");
$comm = new comment();
$comm->assign("act", "main");
$comm->assign("server_name", $_SERVER["SERVER_NAME"]);
$comm->assign("server_ip", $_SERVER['SERVER_ADDR']);
$comm->assign("server_port", $_SERVER["SERVER_PORT"]);
$comm->assign("server_time", date("Y年m月d日H点i分s秒"));
$comm->assign("php_version", PHP_VERSION);
$comm->assign("server_software", $_SERVER["SERVER_SOFTWARE"]);
$comm->assign("server_os", PHP_OS);
$comm->assign("max_time", get_cfg_var("max_execution_time"));
$comm->assign("realpath", realpath("./"));
$comm->assign("upload_max_filesiz", get_cfg_var("upload_max_filesize")?get_cfg_var("upload_max_filesize"):"不允许上传附件");
$comm->assign("post_max_siz", get_cfg_var("post_max_size"));
$comm->assign("server_language", getenv("HTTP_ACCEPT_LANGUAGE"));
$comm->assign("memory_limit", get_cfg_var("memory_limit")?get_cfg_var("memory_limit"):"无");
$comm->assign("mysql", function_exists("mysql_close")?1:0);
$comm->assign("odbc", function_exists("odbc_close")?1:0);
$comm->assign("mssql", function_exists("mssql_close")?1:0);
$comm->assign("msql", function_exists("msql_close")?1:0);
$comm->assign("smtp", get_magic_quotes_gpc("smtp")?1:0);
$comm->assign("gd", function_exists("imageline")?1:0);
$comm->assign("xml", get_magic_quotes_gpc("XML Support")?1:0);
$comm->assign("ftp", get_magic_quotes_gpc("FTP support")?1:0);
$comm->assign("sendmail", get_magic_quotes_gpc("Internal Sendmail Support for Windows 4")?1:0);
$comm->assign("display_error", get_cfg_var("display_errors")?1:0);
$comm->assign("url_fopen", get_cfg_var("allow_url_fopen")?1:0);
$comm->assign("zlib", function_exists("gzclose")?1:0);
$comm->assign("zend", function_exists("zend_version")?1:0);
$comm->display("manage/main.html");*/
header("location:updown.php");
?>