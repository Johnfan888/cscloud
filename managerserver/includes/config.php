<?php
//不允许直接请求该页面
if(!defined('INC')) exit('Bad Request');

	define("DB_HOST",	 "localhost");
	define("DB_USER",	 "root");
	define("DB_PWD",		 "87458106");
	define("DB_NAME",	 "istl");
	
	//定义空间默认根目录ID
	define("ZONE_ROOT", "ROOT");
	@$zone_root = md5(ZONE_ROOT.$_COOKIE['id']);
	//定义空间默认文档目录ID
	define("ZONE_DOC", "DOC");
	@$zone_doc = md5(ZONE_DOC.$_COOKIE['id']);
	//定义空间默认相册目录ID
	define("ZONE_PHOTO", "PHOTO");
	@$zone_photo = md5(ZONE_PHOTO.$_COOKIE['id']);
	//定义空间默认音乐目录ID
	define("ZONE_MUSIC", "MUSIC");
	@$zone_music = md5(ZONE_MUSIC.$_COOKIE['id']);
	//定义空间默认电影目录ID
	define("ZONE_FILM",	 "FILM");
	@$zone_film = md5(ZONE_FILM.$_COOKIE['id']);
?>