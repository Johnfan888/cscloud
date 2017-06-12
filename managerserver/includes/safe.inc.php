<?php
/*
 *	页面安全处理
 * author:张程
 */

//不允许直接请求该页面
if(!defined('INC')) exit('Bad Request');


//检查是否开启php的魔术引号选项，未开启的话做敏感字符处理，不可直接调用该方法，所以以_开头命名
function _RunMagicQuotes(&$svar)
{
    if(!get_magic_quotes_gpc())
    {
        if( is_array($svar) )
        {
            foreach($svar as $_k => $_v) $svar[$_k] = _RunMagicQuotes($_v);
        }
        else
        {
            if( strlen($svar)>0 && preg_match('#^(cfg_|GLOBALS|_GET|_POST|_COOKIE)#',$svar) )
            {
              exit('Request var not allow!');
            }
            $svar = addslashes($svar);
        }
    }
    return $svar;
}

//检查外部提交的变量
function CheckRequest(&$val) {
	if (is_array($val)) 
	{
		foreach ($val as $_k=>$_v)
		{
			if($_k == 'nvarname') continue;
			CheckRequest($_k); 
			CheckRequest($val[$_k]);
		}
	}
	else
	{
		if( strlen($val)>0 && preg_match('#^(cfg_|GLOBALS|_GET|_POST|_COOKIE)#',$val))
		{
			exit('Request var not allow!');
		}
	}
}
    
//每张页面自动运行CheckRequst函数，过滤GET,POST,COOKIE的敏感字符
CheckRequest($_REQUEST);
foreach(Array('_GET','_POST','_COOKIE') as $_request)
{
    foreach($$_request as $_k => $_v)
	{
		${$_k} = _RunMagicQuotes($_v);
	}
}

?>