<?php
$filepath=$_GET['filepath'];

function download($FolderPath)
{//�����ļ��ĺ���,���е�ע�Ͷ��ǵ�����Ҫ�õ���
         
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
//�ж�һ������ǵ�һ�汾���ļ���ֱ�����أ�����������汾���ļ�����Ҫ��������ɺ󣬽��°汾�ļ���ԭ�ɾɰ汾
  
	    download($filepath);











 ?>