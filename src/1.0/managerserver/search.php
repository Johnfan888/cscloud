<?php
/*
 * 搜索文件名页面
 * author:张程
 */


//定义页面必须验证是否登录
define("AUTH", "TRUE");

//载入初始化文件
require(dirname(__FILE__) . "/includes/init.inc.php");

//载入文件处理助手
require(INC_PATH . "/file.helper.inc.php");

if(!empty($_GET['search_name']))
{
	$key = $_GET['search_name'];
	$sql = "select * from T_FileInfo where file_name like '%{$key}%' and user_id='{$_COOKIE['id']}'";
	$list = $db->FetchAssoc($sql);

	//如果搜索到了结果
	if(!empty($list))
	{
		foreach($list as &$arr)
		{
			//如果是文件
			if($arr['file_type']==0)
			{
				$arr['file_url'] = ConvertFileNameToImg($arr['file_name']);
			}
			//如果是文件夹
			else
			{
				$arr['file_url'] = "tp-folder";
			}

			//处理时间，转换为yyyy-dd-mm H:i:s
			$arr['modify_time'] = date('Y-m-d', $arr['modify_time']);

			//处理大小
			$arr['size'] = ComputeSize($arr['size']);

			//处理版本号
			$arr['version'] = "v{$arr['version']}.0";

		}
		$smarty->assign('list', $list);
	}
	//没搜索到结果
	else
	{
		$msg = '<div style="" id="js_no_file_box">
            <div class="page-info">
                <h3><i class="i-hint ico-inf"></i>抱歉，您的空间中没有文件名包含有<font color="orange">'.$key.'</font>的搜索结果</h3>
           </div>
        </div>';
		$smarty->assign("nofile", $msg);
	}
}

$smarty->display('search.html');








?>