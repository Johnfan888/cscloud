<?php
//不允许直接请求该页面
if(!defined('INC')) exit('Bad Request');

/**
 * mysqli数据库类
 * author:张程
 * 所有方法均重写，但命名与mysqli 自身方法名保持一致
*/

class cls_mysqli
{
	var $mysqli;
	var $result;

	function __construct()
	{
		$this->Connect();
	}
	
	//初始化连接
	function Connect()
	{
		$this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);

		if(mysqli_connect_error())
		{
			echo "连接失败，原因为：".mysqli_connect_error();
			$this->mysqli = false;
			exit();
		}
	}

	//执行insert，update，delete操作，返回影响操作的行数
	function Query($sql='')
	{
		if(!empty($sql))
		{
			$this->mysqli->query("set names 'utf8'");
			//检查sql
			$queryString=CheckSql($sql, 'update');
			
			$this->mysqli->query($queryString);
			return $this->mysqli->affected_rows;
		}
		else
			return false;
	}

	//执行select操作，返回二维数组结果集，格式为 array(arrayRow1(), arryRow2() .... )，即使只有一行数据
	function FetchAssoc($sql='')
	{
		if(!empty($sql))
		{
			$arr = array();
			$this->mysqli->query("set names 'utf8'");
			//检查sql
			$queryString = CheckSql($sql);
			$this->result = $this->mysqli->query($queryString);
			if($this->result)
			{
				while($row = $this->result->fetch_assoc())
				{
					$arr[] = $row;
				}
				return $arr;
			}
			else
				return false;
		}
		else
			return false;
	}
	
	//执行select操作，返回一行结果集，格式为 arrayRow1()
	function FetchAssocOne($sql='')
	{
		if(!empty($sql))
		{
			$this->mysqli->query("set names 'utf8'");
			//检查sql
			$queryString=CheckSql($sql);
			$queryString.=" LIMIT 0,1";
			$this->result = $this->mysqli->query($queryString);
			#debug
			#var_dump($this->result);
			#die;
			if($this->result)
			{
				$row = $this->result->fetch_assoc();
				return $row;
			}
			else
				return false;
		}
		else
			return false;
	}

	//获得最后插入的自动增长ID，如果有
	function InsertID()
	{
		return $this->mysqli->insert_id;
	}

	//获得结果集的总行数
	function NumRows($sql='')
	{
		if(!empty($sql))
		{
			$arr = array();
			$this->mysqli->query("set names 'utf8'");
			//检查sql
			$queryString=CheckSql($sql);

			$this->result = $this->mysqli->query($queryString);
			if($this->result)
			{
				return $this->result->num_rows;
			}
			else
				return false;
			
		}
		else
			return false;
	}
	
	//通过执行FetchAssoc操作，获得结果集的总行数
	function NumRowsWithoutSql()
	{
		if($this->result)
			return $this->result->num_rows;
		else
			return false;
	}

	//关闭数据库
    //mysql能自动管理非持久连接的连接池
    //实际上关闭并无意义并且容易出错，所以取消这函数
	function Close()
	{
		if($this->mysqli)
			$this->mysqli->close();
		$this->mysqli = false;
	}

	function __destruct()
	{
		if($this->result)
			$this->result->free();
		#$this->Close();
	}
}
//cls_mysqli.php类结束

	//获得当前Url
    function GetCurUrl()
    {
        if(!empty($_SERVER["REQUEST_URI"]))
        {
            $scriptName = $_SERVER["REQUEST_URI"];
            $nowurl = $scriptName;
        }
        else
        {
            $scriptName = $_SERVER["PHP_SELF"];
            if(empty($_SERVER["QUERY_STRING"])) {
                $nowurl = $scriptName;
            }
            else {
                $nowurl = $scriptName."?".$_SERVER["QUERY_STRING"];
            }
        }
        return $nowurl;
    }
	
	//获得用户IP
	function GetIP()
    {
        static $realip = NULL;
        if ($realip !== NULL)
        {
            return $realip;
        }
        if (isset($_SERVER))
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第x个非unknown的有效IP字符? */
                foreach ($arr as $ip)
                {
                    $ip = trim($ip);
                    if ($ip != 'unknown')
                    {
                        $realip = $ip;
                        break;
                    }
                }
            }
            elseif (isset($_SERVER['HTTP_CLIENT_IP']))
            {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            }
            else
            {
                if (isset($_SERVER['REMOTE_ADDR']))
                {
                    $realip = $_SERVER['REMOTE_ADDR'];
                }
                else
                {
                    $realip = '0.0.0.0';
                }
            }
        }
        else
        {
            if (getenv('HTTP_X_FORWARDED_FOR'))
            {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            }
            elseif (getenv('HTTP_CLIENT_IP'))
            {
                $realip = getenv('HTTP_CLIENT_IP');
            }
            else
            {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = ! empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realip;
    }

	//SQL语句过滤程序
	function CheckSql($db_string,$querytype='select')
    {
        $clean = '';
        $error='';
        $old_pos = 0;
        $pos = -1;
        $log_file = ROOT_PATH.'/sql_safe_log.txt';
		$userIP = GetIP();
        $getUrl = GetCurUrl();

        //如果是普通查询语句，直接过滤一些特殊语法
        if($querytype=='select')
        {
            $notallow1 = "[^0-9a-z@\._-]{1,}(union|sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}";

            //$notallow2 = "--|/\*";
            if(preg_match("/".$notallow1."/", $db_string))
            {
                fputs(fopen($log_file,'a+'),"$userIP||$getUrl||$db_string||SelectBreak\r\n");
                exit("<font size='5' color='red'>Safe Alert: Request Error step 1 !</font>");
            }
        }

        //完整的SQL检查
        while (TRUE)
        {
            $pos = strpos($db_string, '\'', $pos + 1);
            if ($pos === FALSE)
            {
                break;
            }
            $clean .= substr($db_string, $old_pos, $pos - $old_pos);
            while (TRUE)
            {
                $pos1 = strpos($db_string, '\'', $pos + 1);
                $pos2 = strpos($db_string, '\\', $pos + 1);
                if ($pos1 === FALSE)
                {
                    break;
                }
                elseif ($pos2 == FALSE || $pos2 > $pos1)
                {
                    $pos = $pos1;
                    break;
                }
                $pos = $pos2 + 1;
            }
            $clean .= '$s$';
            $old_pos = $pos + 1;
        }
        $clean .= substr($db_string, $old_pos);
        $clean = trim(strtolower(preg_replace(array('~\s+~s' ), array(' '), $clean)));

        //老版本的Mysql并不支持union，常用的程序里也不使用union，但是一些黑客使用它，所以检查它
        if (strpos($clean, 'union') !== FALSE && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="union detect";
        }

        //发布版本的程序可能比较少包括--,#这样的注释，但是黑客经常使用它们
        elseif (strpos($clean, '/*') > 2 || strpos($clean, '--') !== FALSE || strpos($clean, '#') !== FALSE)
        {
            $fail = TRUE;
            $error="comment detect";
        }

        //这些函数不会被使用，但是黑客会用它来操作文件，down掉数据库
        elseif (strpos($clean, 'sleep') !== FALSE && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="slown down detect";
        }
        elseif (strpos($clean, 'benchmark') !== FALSE && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="slown down detect";
        }
        elseif (strpos($clean, 'load_file') !== FALSE && preg_match('~(^|[^a-z])load_file($|[^[a-z])~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="file fun detect";
        }
        elseif (strpos($clean, 'into outfile') !== FALSE && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="file fun detect";
        }

        //老版本的MYSQL不支持子查询，我们的程序里可能也用得少，但是黑客可以使用它来查询数据库敏感信息
        elseif (preg_match('~\([^)]*?select~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="sub select detect";
        }
        if (!empty($fail))
        {
            fputs(fopen($log_file,'a+'),"$userIP||$getUrl||$db_string||$error\r\n");
            exit("<font size='5' color='red'>Safe Alert: Request Error step 2!</font>");
        }
        else
        {
            return $db_string;
        }
    }
?>