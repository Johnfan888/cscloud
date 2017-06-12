<?php
//不允许直接请求该页面
if(!defined('INC')) exit('Bad Request');

/**
 * 处理用户信息的一些方法
 * author:张程
 */

 //用户空间信息
 function UserZoneInfo($uid)
 {
	$sql = "select * from T_UserZone where user_id='{$uid}'";
	$userzone = $GLOBALS['db']->FetchAssocOne($sql);
	return $userzone;
 }

//剩余空间
 function RemainSize($uid)
 {
	 $info = UserZoneInfo($uid);
	 return $info['useable_size']-$info['used_size'];
 }

 //查询用户存放文件的服务器IP
 function UserFileIP($uid)
 {
	 $info = UserZoneInfo($uid);
	 return $info['server_ip'];
 }

 //查询用户存放文件的副本服务器IP
 function UserHAFileIP($uid)
 {
	 $info = UserZoneInfo($uid);
	 return $info['ha_server_ip'];
 }

 //更新用户服务器IP信息
 function UpdateFileIP($uid, $ip)
 {
	 $sql = "update T_UserZone set server_ip='{$ip}' where user_id='{$uid}'";
	 $GLOBALS['db']->Query($sql);
 }

 //更新用户副本服务器IP信息
 function UpdateHAFileIP($uid, $ip)
 {
	 $sql = "update T_UserZone set ha_server_ip='{$ip}' where user_id='{$uid}'";
	 $GLOBALS['db']->Query($sql);
 }