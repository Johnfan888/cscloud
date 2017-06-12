<!doctype html>
<html>
	<head></head>
	<body>
		<?php
		/*
		 * 处理文件下载
		 * author:张程
		 */
		if(isset($_GET['path']))
		{
			$path = $_GET['path']; //
			$user = $_GET['user'];
			$id = $_GET['id'];
			$path = rtrim($path, '/');
			//添加name
			$name1=rawurldecode($_GET['name']);
			//分解目录
			$arr=str_split($id,8);
				$arr0=substr($arr[0], -1);
				$arr1=substr($arr[1], -1);
				$arr2=substr($arr[2], -1);
				$arr3=substr($arr[3], -1);
				$arr0=md5($arr0);
				$arr1=md5($arr1);
				$arr2=md5($arr2);
				$arr3=md5($arr3);
		   		$targetPath = "{$path}/{$user}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/{$id}"; //目标路径
		  		
		    if(!file_exists($targetPath))  
		    { 
				header("Content-type:text/html; Charset=utf-8");
		        echo '对不起,你要播放的文件不存在。';  
		    }
		   
			//获取文件扩展名
			$name = !empty($_GET['name']) ? rawurldecode($_GET['name']) : 'downloaded'; //解码
		
			 if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) //用户的浏览器 操作系统信息 
				 $name = rawurlencode($name);
			//判断一下如果是第一版本的文件则直接下载，如果是其他版本的文件，则要在下载完成后，将新版本文件还原成旧版本
			clearstatcache();
			$file_path = pathinfo($name1);
		/*	if($file_path['extension'] == "doc" || $file_path['extension'] == "txt" || $file_path['extension'] == "pdf" || $file_path['extension'] == "jpg" || $file_path['extension'] == "jpeg"
			 || $file_path['extension'] == "png" || $file_path['extension'] == "gif" || $file_path['extension'] == "xls" || $file_path['extension'] == "ppt"){

				echo "<script>alert('一般文件请使用下载功能下载后查看!');window.close();</script>";
			}
			else{*/
				/* $OnlinePlayFile="../OnlinePlayFile/{$id}";
			    if(!file_exists($OnlinePlayFile)){
			    	$cmd="ln -s ".$targetPath." ".$OnlinePlayFile;
			    	exec($cmd);
			    }*/
				$playpath=explode('/',$targetPath,2);
				$OnlinePlayFile="../".$playpath['1'];
				?>
				<video src="<?php echo $OnlinePlayFile?>" controls="controls" id="video" poster="./error.jpg" autoplay="autoplay">
				</video>
				<?php 
		//	}
			
		}
		//下载文件的函数,其中的注释都是调试中要用到的
		function download($file, $filename)
		{
		if(!file_exists($file))  
		    { 
				 header("Content-type:text/html; Charset=utf-8");
		        echo '对不起,你要下载的文件不存在。';  
		    }
			else
			{
				header('Expires: 0');
				header('Content-type: application/octet-stream');
				header("Content-Disposition: attachment; filename={$filename}");
				header('Content-Transfer-Encoding: binary');
				@readfile($file);
			}
		}
		?>
</body>
</html>