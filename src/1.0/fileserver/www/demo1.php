<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>传递地址参数</title>
<style type="text/css">


body
{
	text-align : center;
	padding : 50px;
	background-color : lightblue;
}
div
{
	margin : 10px;
	border : 0px;
	color : #333333;
	font-size : 12px;
}
.title
{
	color : #ff5555;
	font-size : 27px;
}
.author
{
	color : #ee6666;
	font-size : 16px;
}

</style>
</head>

<body>
<table>
<tr><td>
<?php 

$totalsize=$_GET["totalsize"];
$usedsize=$_GET["usedsize"];

if($totalsize>=1000000000)
{
	$totalsize=round($totalsize/1000000000,2);
	$unit="GB";
}
else if($totalsize>=1000000)
{ 
	$totalsize=round($totalsize/1000000,2);
	$unit="MB";
}else if($totalsize>=1000)
{
	$totalsize=round($totalsize/1000,2);
	$unit="KB";
}else if($totalsize>=0)
{
	$unit="Byte";
}

if($usedsize>=1000000000)
{
	$usedsize=round($usedsize/1000000000,2);
	$unit1="GB";
}
else if($usedsize>=1000000)
{ 
	$usedsize=round($usedsize/1000000,2);
	$unit1="MB";
}else if($usedsize>=1000)
{
	$usedsize=round($usedsize/1000,2);
	$unit1="KB";
}else
{
	$unit1="Byte";
}
echo "<strong>totalsize:</strong>".$totalsize.$unit;
echo ",<strong>usedsize:</strong>".$usedsize.$unit1;

 ?>
</td></tr>
<tr><td>
<embed 
    src="upload.swf?filetype=all&maxsize=10000000&parent_id=<?php echo $_GET['parent_id'];?>&dirpath=<?php echo $_GET['dirpath'];?>&owner=<?php echo $_GET['owner'];?>&replicaip=<?php echo $_GET['replicaip'];?>&replicapath=<?php echo $_GET['replicapath'];?>&manageserverip=<?php echo $_GET['manageserverip'];?>&totalsize=<?php echo $_GET["totalsize"];?>&userfilepath=<?php echo $_GET['userfilepath'];?>"
    width="300"
    height="200"
    align="middle"
    play="true"
    loop="false"
    quality="high"
    type="application/x-shockwave-flash">
</embed>
</td></tr>
<tr><td>
<p><a href="http://<?php echo $_GET['manageserverip'];?>/manage/updown.php?owner=<?php echo $_GET['owner'];?>">
返回用户文件管理页面查看备份结果</a></p>
</td></tr>

</body>
</html>
