<?php
 error_reporting(E_ALL^E_NOTICE^E_WARNING);

	@$step = $_REQUEST["step"];
    $stepNum = 0;
    /// <summary>
    /// 服务器环境检测结果json
    /// </summary>

    @$adminName = $_POST["adminname"];
    @$adminPassword = $_POST["adminpassword"];
	
	if (file_exists("install.lock"))
		 exit();

	switch ($step)
	{
		case "forumset":
			$stepNum = 2;
			break;
		case "initial":
			$stepNum = 3;
			InitCMS($adminName, $adminPassword);
			break;
		default:
			$stepNum = 1;
			break;
	}

	function InitCMS($adminName, $adminPassword)
	{
		require("../includes/init.inc.php");
		$sql = "insert into T_User (email, password, is_admin, is_checked) values ('{$adminName}', md5('{$adminPassword}'), 1, 1)";

		//这里$db不需要$GLOBALS的原因是在function中require的init.inc.php文件，作用域在函数内
		if($db->Query($sql) > 0)
		{
			$id = $db->InsertID();
			$time = time();
			$root = md5(ZONE_ROOT.$id);
			$doc = md5(ZONE_DOC.$id);
			$photo = md5(ZONE_PHOTO.$id);
			$music = md5(ZONE_MUSIC.$id);
			$film = md5(ZONE_FILM.$id);
			$sql = "insert into T_FileInfo values ('{$root}', '我的云盘', '0', '', 1, 0, 1, $time, {$id}, 0),  ('{$doc}', '我的文档', '0', '', 1, 0, 1, $time, {$id}, 0), ('{$photo}', '我的相册', '0', '', 1, 0, 1, $time, {$id}, 0), ('{$music}', '我的音乐', '0', '', 1, 0, 1, $time, {$id}, 0), ('{$film}', '我的视频', '0', '', 1, 0, 1, $time, {$id}, 0)";
			if($db->Query($sql) > 0)
			{
				$sql = "insert into T_UserZone (user_id, useable_size) values('{$id}', '10000000000')";
				$db->Query($sql);
				
				 //读取fileserver配置文件
				 if($ips = @fopen(ROOT_PATH . '/install/server.txt' ,'r'))
				 {
					//读取默认路径
					$file = ROOT_PATH . '/admin/config/server.txt';
					$config = @fopen($file, 'r');
					if($config)
					{
						if(!feof($config))
						{
							$defaultpath = fgets($config);
							$defaultpath = trim(strstr($defaultpath, '/'));
						}
						fclose($config);
					}
					
					//读取IP
					$ip = fgets($ips);
					$ips = json_decode($ip);
					foreach($ips as $row)
					{
						$sql = "insert into T_Server values (NULL, '{$row}', '{$defaultpath}', '1')";
						$db->Query($sql);
					}
					fclose($ips);
				 }
				file_put_contents("install.lock", "");
			}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>安装程序</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link href="main.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="js/jquery.js"></script>

    <link rel="stylesheet" href="js/jquery_boxy/css/common.css" type="text/css" />
    <link rel="stylesheet" href="js/jquery_boxy/css/boxy.css" type="text/css" />

    <script type="text/javascript" src="js/jquery_boxy/js/jquery.boxy.js"></script>

</head>
<body>
    <div class="nav">
        <ul>			
            <li <?php if($stepNum==1){ ?> class="cur" <?php }else if($stepNum>1){ ?> class="finish" <?php } ?>>
                <span>数据库配置</span></li>
            <li <?php if($stepNum==2){ ?> class="cur" <?php }else if($stepNum>3){ ?> class="finish" <?php } ?>>
                <span>管理系统配置</span></li>
            <li <?php if($stepNum==3){ ?> class="cur" <?php }else if($stepNum>4){ ?> class="finish" <?php } ?>>
                <span>安装</span></li>
        </ul>
    </div>
    <?php if ($stepNum == 1)
      { ?>
    <div class="main cl">
        <h1>
            数据库检测</h1>
        <div class="inner">
            <form action="" method="post">
            <table width="100%" cellspacing="0" cellpadding="0" summary="数据库配置">
                <tbody>
                    <tr>
                        <td class="title">
                            数据库地址:
                        </td>
                        <td>
                            <input id="sql_ip" name="sql_ip" class="txt" type="text" value="localhost" />
                            <br />
                            <span>本机请填写localhost</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="title">
                            数据库用户名:
                        </td>
                        <td>
                            <input id="sql_username" name="sql_username" class="txt" type="text" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td class="title">
                            数据库密码:
                        </td>
                        <td>
                            <input id="sql_password" name="sql_password" class="txt" type="password" value="" /><br />
                            <span>如果你没有数据库用户名与密码请进入数据库创建</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="title">
                            要创建的数据库名称:
                        </td>
                        <td>
                            <input id="dbname" name="dbname" class="txt" type="text" value="" /><br />
                        </td>
                    </tr>
                </tbody>
            </table>
            </form>
        </div>
    </div>
    <div class="btn cl">
        <a href="#" onclick="checkDbset();" class="next">下一步</a>
    </div>

    <script type="text/javascript">
        var showbox;
        var alerthead = "<p style=\"width:300px;font-size:14px;\"><img src=\"images/loading.gif\" alt=\"loading\" />";
        var createdDb = 0;
        var runstep = 0;
        var steptime = 700;

        function checkDbset() {
            var sqlip = $('#sql_ip').val();
            var loginname = $('#sql_username').val();
            var password = $('#sql_password').val();
            var dbname = $('#dbname').val();

            if (sqlip == "") {
                Boxy.alert('数据库地址不能为空', false, { width: 400 });
                return;
            }
            if (loginname == "") {
                Boxy.alert('数据库登录名不能为空', false, { width: 400 });
                return;
            }
            if (password == "") {
                Boxy.alert('数据库登录密码不能为空', false, { width: 400 });
                return;
            }
            if (dbname == "") {
                Boxy.alert('数据库名称不能为空', false, { width: 400 });
                return;
            }

            showbox = new Boxy(alerthead + "正在检测数据库连接,该检测可能比较耗时,请耐心等待......</p>", { closeable: false, modal: true, center: true });

            checkDbAjax(sqlip, loginname, password, dbname);
        }

        function checkDbAjax(sqlip, loginname, password, dbname) {
            showbox.show();
            showbox.setContent(alerthead + "正在检测数据库连接,该检测可能比较耗时,请耐心等待......</p>");
            jQuery.get('ajax.php', { 't': 'checkdbconnection', 'ip': sqlip, 'loginname': loginname, 'loginpwd': password, 'dbname': dbname, 'time': Math.random() },
            function(data) {
                var callback = eval('(' + data + ')');
                if (callback.result) {
                    DBSourceExist(sqlip, loginname, password, dbname);
                } else {
                    showbox.hide();
                    Boxy.alert(data, null, { width: 400 });
                }
            });
        }

        function DBSourceExist(sqlip, loginname, password, dbname) {
            showbox.show();
            showbox.setContent(alerthead + "正在检测数据库已有数据......</p>");
            jQuery.get('ajax.php', { 't': 'dbsourceexist', 'ip': sqlip, 'loginname': loginname, 'loginpwd': password, 'dbname': dbname, 'time': Math.random() },
                function(data) {
                    var callback = eval('(' + data + ')');
                    if (!callback.result) {
                            createDbAjax(sqlip, loginname, password, dbname);
                    }
                    else {
                        showbox.hide();
                        Boxy.confirm('系统检测到数据库' + dbname + '已经所需的数据表,继续安装会清空之前数据,是否继续?', function o() {
							createTable(sqlip, loginname, password, dbname);
                        }, { title: "是否继续安装?" });
                    }
                });
        }

        function createDbAjax(sqlip, loginname, password, dbname) {
            showbox.show();
            showbox.setContent(alerthead + "正在创建数据库......</p>");
            jQuery.get('ajax.php', { 't': 'createdb', 'ip': sqlip, 'loginname': loginname, 'loginpwd': password, 'dbname': dbname, 'time': Math.random() },
                function(data) {
                    var callback = eval("(" + data + ")");
                    if (callback.result) {
                        createTable(sqlip, loginname, password, dbname);
                    } else if (callback.code == "262") {
                        showbox.hide();
                        Boxy.alert('数据库用户 \'' + loginname + '\' 没有创建数据库的权限,创建新数据库失败 ', null, { width: 400 });
                    } else if (callback.code == "1801") {
                        showbox.hide();
                        Boxy.alert('数据库已存在，创建新数据库失败 ', null, { width: 400 });
                    }
                    else {
                        showbox.hide();
                        Boxy.alert(data, null, { width: 400 });
                    }
                }
            );
        }

        function createTable(sqlip, loginname, password, dbname) {
            showbox.show();
            showbox.setContent(alerthead + "正在创建数据表......</p>");
            jQuery.get('ajax.php', { 't': 'createtable', 'ip': sqlip, 'loginname': loginname, 'loginpwd': password, 'dbname': dbname, 'time': Math.random() },
                function(data) {
                    var callback = eval("(" + data + ")");
                    if (callback.result) {
                        showbox.hide();
                        Boxy.alert('数据库及表创建成功 ', function o() {
                            location.href = "index.php?step=forumset"
                        }, { width: 400 });
                    }
                    else {
                        showbox.hide();
                        Boxy.alert(data, null, { width: 400 });
                    }
                }
            );
        }
    </script>

    <?php }
      else if ($stepNum == 2)
      { ?>
    <div class="main cl">
        <h1>
            管理系统设置</h1>
        <div class="inner">
            <form id="forumset" action="" method="post">
            <table width="100%" cellspacing="0" cellpadding="0" summary="系统设置">
                <tbody>
                    <tr>
                        <td class="title">
                            管理员邮箱:
                        </td>
                        <td>
                            <input id="adminname" name="adminname" class="txt" type="text" />
                        </td>
                    </tr>
                    <tr>
                        <td class="title">
                            管理员密码:
                        </td>
                        <td>
                            <input id="adminpassword" name="adminpassword" class="txt" type="password" />
                        </td>
                    </tr>
                    <tr>
                        <td class="title">
                            管理员密码确认:
                        </td>
                        <td>
                            <input id="confirmpassword" name="confirmpassword" class="txt" type="password" />
                        </td>
                    </tr>
                </tbody>
            </table>
            </form>
        </div>
    </div>
    <div class="btn cl">
        <a href="javascript:history.back();" class="back">上一步</a> <a href="###" onclick="checkforumset();"
            class="next">下一步</a>
    </div>

    <script type="text/javascript">
        function checkforumset() {
            var adminname = $('#adminname').val();
            var adminpassword = $('#adminpassword').val();
            var confirmpassword = $('#confirmpassword').val();

            if (adminname == "") {
                Boxy.alert('管理员名称不能为空', false, { width: 400 });
                return;
            }
            if (adminpassword == "") {
                Boxy.alert('管理员密码不能为空', false, { width: 400 });
                return;
            }
            if (adminpassword != confirmpassword) {
                Boxy.alert('两次输入的密码不一致', false, { width: 400 });
                return;
            }
            $('#forumset').attr("action", "index.php?step=initial");
            $('#forumset').submit();
        }
    </script>

    <?php
	  }
      else if ($stepNum == 3)
      {?>
    <div class="main cl">
        <h1>
            安装完毕</h1>
        <div class="inner">
            <ul id="processlist" class="list">
                <li>请牢记您的用户名:
                    <?php echo $adminName ?></li>
                <li>为了安全，建议您安装完后删除Install文件夹及文件夹下的所有文件</li>
            </ul>
        </div>
    </div>
    <div class="btn cl">
        <a href="###" class="back">上一步</a> <a id="successlink" href="../login.php" class="back">
            完成</a>
    </div>
    <?php } ?>
</body>
</html>
