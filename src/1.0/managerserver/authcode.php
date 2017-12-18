<?php
/**
 * 验证码生成
 * author: 张程
*/

//载入初始化文件
require(dirname(__FILE__) . "/includes/init.inc.php");

//创建一个新图形
$im = @imagecreate(100,25) or die();
//设置背景,分配颜色
$bgColor = imagecolorallocate($im, 255, 255, 255);
//字体颜色
$textColor = imagecolorallocate($im, mt_rand(0,100), mt_rand(0,150), mt_rand(0,200));

//填充背景色
imagefilledrectangle($im, 0, 0, 99, 24, $bgColor);

//生成随机数
$number = rand(1000, 9999);

//保存session
$_SESSION['authcode'] = $number;

//生成字符串图片
imagestring($im, 24, 5, 5, $number, $textColor);
//输出图形
imagepng($im);
//销毁
imagedestory($im);

?>