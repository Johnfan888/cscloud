<?php
/*  $client = $_POST['client'];
//$temp = explode(".", $_FILES["file"]["name"]);
//echo $_FILES["file"]["size"];
//$extension = end($temp);     // 获取文件后缀名
	if ($_FILES["file"]["error"] > 0)
	{
		echo "错误：: " . $_FILES["file"]["error"] . "<br>";
	}
	else
	{
	 	echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
		echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
		echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";

		
		
		// 判断当期目录下的 upload 目录是否存在该文件
		// 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
		if (file_exists("upload/" . $_FILES["file"]["name"]))
		{
			echo $_FILES["file"]["name"] . " 文件已经存在。 ";
		}
		else
		{

			move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/" . $_FILES["file"]["name"]);
			echo $_FILES[0][0];
		}
	} */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$ret=array('strings'=>$_POST,'error'=>'0');

	$fs=array();

	foreach ( $_FILES as $name=>$file ) {

		$fn=$file['name'];
		$fp='uploads/'.$fn;
		move_uploaded_file($file['tmp_name'],$fp);
		$fs[$name]=array('name'=>$fn,'url'=>$fp,'type'=>$file['type'],'size'=>$file['size']);
	}

	$ret['files']=$fs;

	echo json_encode($ret);
}else{
	echo "{'error':'Unsupport GET request!'}";
}
?>