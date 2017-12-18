<?php
$filepath=$_GET['filepath'];

function download($FolderPath)
{//下载文件的函数,其中的注释都是调试中要用到的
         
		  /*  $fpp=fopen("aaa.txt","a");
			fwrite($fpp,"This is a test!\n");*/
			
            header("Pragma: public");
			//fwrite($fpp,"1\n");
			header("Expires: 0");
			//fwrite($fpp,"2\n");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			//fwrite($fpp,"3\n");
			header("Content-Type: application/force-download");
			//fwrite($fpp,"4\n");
			header("Content-Type: application/octet-stream");
			//fwrite($fpp,"5\n");
			header("Content-Type: application/download");
			//fwrite($fpp,"6\n");
			//header("Content-Disposition: attachment; filename=$FileName");
			//fwrite($fpp,"7\n");
			header("Content-Transfer-Encoding: binary");
			//fwrite($fpp,"8\n");
			readfile($FolderPath);
			/*fwrite($fpp,"9\n");
			fclose($fpp);*/
}
//判断一下如果是第一版本的文件则直接下载，如果是其他版本的文件，则要在下载完成后，将新版本文件还原成旧版本
  
	    download($filepath);











 ?>