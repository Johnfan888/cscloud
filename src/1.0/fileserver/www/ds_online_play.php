<!doctype html>
<html>
	<head></head>
	<body>
		<?php
		/*
		 * �����ļ�����
		 * author:�ų�
		 */
		if(isset($_GET['path']))
		{
			$path = $_GET['path']; //
			$user = $_GET['user'];
			$id = $_GET['id'];
			$path = rtrim($path, '/');
			//���name
			$name1=rawurldecode($_GET['name']);
			//�ֽ�Ŀ¼
			$arr=str_split($id,8);
				$arr0=substr($arr[0], -1);
				$arr1=substr($arr[1], -1);
				$arr2=substr($arr[2], -1);
				$arr3=substr($arr[3], -1);
				$arr0=md5($arr0);
				$arr1=md5($arr1);
				$arr2=md5($arr2);
				$arr3=md5($arr3);
		   		$targetPath = "{$path}/{$user}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/{$id}"; //Ŀ��·��
		  		
		    if(!file_exists($targetPath))  
		    { 
				header("Content-type:text/html; Charset=utf-8");
		        echo '�Բ���,��Ҫ���ŵ��ļ������ڡ�';  
		    }
		   
			//��ȡ�ļ���չ��
			$name = !empty($_GET['name']) ? rawurldecode($_GET['name']) : 'downloaded'; //����
		
			 if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) //�û�������� ����ϵͳ��Ϣ 
				 $name = rawurlencode($name);
			//�ж�һ������ǵ�һ�汾���ļ���ֱ�����أ�����������汾���ļ�����Ҫ��������ɺ󣬽��°汾�ļ���ԭ�ɾɰ汾
			clearstatcache();
			$file_path = pathinfo($name1);
		/*	if($file_path['extension'] == "doc" || $file_path['extension'] == "txt" || $file_path['extension'] == "pdf" || $file_path['extension'] == "jpg" || $file_path['extension'] == "jpeg"
			 || $file_path['extension'] == "png" || $file_path['extension'] == "gif" || $file_path['extension'] == "xls" || $file_path['extension'] == "ppt"){

				echo "<script>alert('һ���ļ���ʹ�����ع������غ�鿴!');window.close();</script>";
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
		//�����ļ��ĺ���,���е�ע�Ͷ��ǵ�����Ҫ�õ���
		function download($file, $filename)
		{
		if(!file_exists($file))  
		    { 
				 header("Content-type:text/html; Charset=utf-8");
		        echo '�Բ���,��Ҫ���ص��ļ������ڡ�';  
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