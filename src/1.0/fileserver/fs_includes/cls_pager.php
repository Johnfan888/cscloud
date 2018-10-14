<?php
//不允许直接请求该页面
if(!defined('INC')) exit('Bad Request');

/**
 * 分页类
 * author:张程
*/

class cls_pager
{
	//分页大小
	private $page_size;
	//待分页的记录总数
	private $count;
	//当前请求页面的uri
	private $uri;

	function __construct($count, $size=20, 

}




?>