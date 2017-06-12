<?php
		header("Content-Type:text/html;charset=utf-8");
		define("ROOT_PATH", str_replace("install/ajax.php", "", str_replace("\\", "/", __FILE__)));
		$t = $_GET["t"];

        $sqlIp = $_GET["ip"];
        $sqlLoginName = $_GET["loginname"];
        $sqlLoginAuth = $_GET["loginpwd"];
        $dbName = $_GET["dbname"];

        switch ($t)
        {
            case "checkdbconnection"://检查数据库连接
                CheckDBConnection($sqlIp, $sqlLoginName, $sqlLoginAuth);
                break;
            case "createdb"://创建新的数据库
                CreateDatabase($sqlIp, $sqlLoginName, $sqlLoginAuth, $dbName);
                break;
            case "dbsourceexist":
                DBSourceExist($sqlIp, $sqlLoginName, $sqlLoginAuth, $dbName);
                break;
            case "createtable"://创建论坛数据表
                CreateTable($sqlIp, $sqlLoginName, $sqlLoginAuth, $dbName);
                break;
        }

		function CheckDBConnection($sqlIp, $sqlUsername, $sqlPassword)
		{
			 @$conn = mysql_connect($sqlIp,$sqlUsername,$sqlPassword) or die('{result:false,message:"'.mysql_error().'",code:"' . mysql_errno() . '"}');
			 echo '{result:true,message:"连接成功"}';
		}

		function DBSourceExist($sqlIp, $sqlUsername, $sqlPassword, $databaseName)
		{
			$conn = mysql_connect($sqlIp,$sqlUsername,$sqlPassword) or die('{result:false,code:"' . mysql_errno() . '"}');

			mysql_select_db($databaseName) or die('{result:false,code:"' . mysql_errno() . '"}');
			
			echo "{result:true,message:\"数据库已存在\",code:0}";
		}

		function CreateDatabase($sqlIp, $sqlUsername, $sqlPassword, $databaseName)
		{
			$conn = mysql_connect($sqlIp,$sqlUsername,$sqlPassword) or die('{result:false,code:"' . mysql_errno() . '"}');
			mysql_query("CREATE DATABASE IF NOT EXISTS {$databaseName}") or die('{result:false,code:"' . mysql_errno() . '"}');
			echo "{result:true,message:\"数据库创建成功\"}";
		}

		function CreateTable($sqlIp, $sqlUsername, $sqlPassword, $databaseName)
		{
			$conn = mysql_connect($sqlIp,$sqlUsername,$sqlPassword) or die('{result:false,code:"' . mysql_errno() . '"}');
			mysql_select_db($databaseName) or die('{result:false,code:"' . mysql_errno() . '"}');

			mysql_query("SET NAMES 'utf8'");
			$sql=file_get_contents("sql-tables.sql");
			foreach(explode(";", trim($sql)) as $query)
			{
				mysql_query($query) or die('{result:false,code:"' . mysql_errno() . '"}');
			}
			
			$config = file_get_contents("config.txt");
			$config = str_replace("@HOST", $sqlIp, $config);
			$config = str_replace("@USER", $sqlUsername, $config);
			$config = str_replace("@PWD", $sqlPassword, $config);
			$config = str_replace("@NAME", $databaseName, $config);
			$file = fopen(ROOT_PATH . '/includes/config.php', 'w');
			fwrite($file, $config);
			fclose($file);
			echo "{result:true,message:\"数据库及表创建成功\"}";
		}
?>