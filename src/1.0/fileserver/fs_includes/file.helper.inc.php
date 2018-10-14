<?php
//不允许直接请求该页面
if(!defined('INC')) exit('Bad Request');

/**
 * 文件处理类
 * author:张程
*/


//寻找文件名对应的缩略图片
function ConvertFileNameToImg($name){
	$imgurl = "tp-unknow";
	if(!empty($name))
	{
		//获取文件扩展名
		$path = pathinfo($name);
		@$extension = strtolower($path["extension"]);
		switch($extension){
			case "png":
				$imgurl = "tp-png";
				break;
			case "jpg":
				$imgurl = "tp-png";
				break;
			case "jpeg":
				$imgurl = "tp-png";
				break;
			case "gif":
				$imgurl = "tp-png";
				break;
			case "bmp":
				$imgurl = "tp-png";
				break;
			case "rar":
				$imgurl = "tp-tar";
			case "tar":
				$imgurl = "tp-tar";
				break;
			case "zip":
				$imgurl = "tp-tar";
				break;
			case "doc":
				$imgurl = "tp-dotm";
				break;
			case "docx":
				$imgurl = "tp-dotm";
				break;
			case "xls":
				$imgurl = "tp-xlsb";
				break;
			case "xlsx":
				$imgurl = "tp-xlsb";
				break;
			case "ppt":
				$imgurl = "tp-pptm";
				break;
			case "pptx":
				$imgurl = "tp-pptm";
				break;
			case "pdf":
				$imgurl = "tp-pdf";
				break;
			case "mp3":
				$imgurl = "tp-ogg";
				break;
			case "mp4":
				$imgurl = "tp-ogg";
				break;
			case "wma":
				$imgurl = "tp-ogg";
				break;
			case "exe":
				$imgurl = "tp-bat";
				break;
			case "flv":
				$imgurl = "tp-fla";
				break;
			case "html":
				$imgurl = "tp-chm";
				break;
			case "htm":
				$imgurl = "tp-chm";
				break;
			case "php":
				$imgurl = "tp-chm";
				break;
			case "jsp":
				$imgurl = "tp-chm";
				break;
			case "aspx":
				$imgurl = "tp-chm";
				break;
			case "iso":
				$imgurl = "tp-dmg";
				break;
			case "psd":
				$imgurl = "tp-psd";
				break;
			case "txt":
				$imgurl = "tp-txt";
				break;
			case "rmvb":
				$imgurl = "tp-dat";
				break;
			case "avi":
				$imgurl = "tp-dat";
				break;
			case "mpg":
				$imgurl = "tp-dat";
				break;
		}
	}
	return $imgurl;
}

//处理文件大小单位
function ComputeSize($s)
{
	$size = $s;
	$unit = "Byte";
	if($size>1024)
	{
		$size = round($size/1024, 2);
		$unit = "KB";
	}
	if($size>1024)
	{
		$size = round($size/1024, 2);
		$unit = "MB";
	}
	if($size>1024)
	{
		$size = round($size/1024, 2);
		$unit = "GB";
	}
	if($size>1024)
	{
		$size = round($size/1024, 2);
		$unit = "TB";
	}
	return $size.' '.$unit;
}

//生成唯一的文件名
function UniqueName()
{ 
	$token = md5(uniqid(rand()));
	return $token;
}
?>