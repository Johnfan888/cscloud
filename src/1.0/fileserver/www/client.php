<?php
if($_POST["clientstage"]=="")
{
   $clientstage=$_GET["clientstage"];
}
else{
   $clientstage=$_POST["clientstage"];
}


 require("configure_class.php");
$c = new Configuration();
$c->_construct();
$webserverip=trim($c->_get("ManagerServerIP"));
$originip=trim($c->_get("ServerIP"));
$PublicPath= $c->_get("UserFilePath"); //����·��
if($clientstage==1)
//˵���ǵ�һ��Ӧ�ý������룬��������洢����
{
    $fp=fopen("/var/log/csc/clientstage.txt","w");//�ѷ��ص�����д���ļ�
    fwrite($fp,$clientstage."\n");
    fclose($fp);
      
	$check=$_GET['password'];
	$stage=$_GET['stage'];
	$targetip=$_GET['targetip'];
	$stage=$stage+1;
	//�������¼������
	$fp=fopen("/var/log/csc/checkpass.txt","w");//�ѷ��ص�����д���ļ�
    fwrite($fp,$check."\n");
	fwrite($fp,$targetip);
    fclose($fp);
	$get_url="http://".$webserverip."/manage/transfer.php?stage=".$stage."&clientstage=".$clientstage;
		$ch = curl_init();
		$ch = curl_init($get_url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 

			if ($output===FALSE) {//������,д�������־��
			 $fp=fopen("/var/log/csc/clientstage.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		");
			   fwrite($fp,"transfer  ");
			  fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);
}
else if($clientstage==2)
//˵��Ӧ�ý����ļ�����Ϣ,���ѽ��յ����ļ���Ϣд��sendfileinfo.txt��
{
	$fp=fopen("/var/log/csc/clientstage.txt","a");//�ѷ��ص�����д���ļ�
    fwrite($fp,$clientstage."\n");
    fclose($fp);
   

	$cou=(count($_POST)-2);//һ���м�������Ԫ��
	//echo "<br>file count is ".$cou."<br>";
	//ȡ��ǰ3����ʣ�µĶ����й���Ҫ�ϴ��ļ�����Ϣ
	$stage=$_POST["stage"]+1;
	//print_r($_POST);
	$fp=fopen("/var/log/csc/sendfileinfo.txt","w");//����Ҫ������ļ�����Ϣд���ļ�
	$arrfilename=array();
	for($i=0;$i<$cou;$i++)
	{
	  $name="file".$i;
	  //$arrfilename["$name"]=trim($_POST["$name"]);
	  $arrfilename["$name"]=$_POST["$name"];
	  fwrite($fp,$arrfilename["$name"]."\n");
	}
	fclose($fp);
	//echo $stage."\n";
	//echo $clientstage;
	$get_url="http://".$webserverip."/manage/transfer.php?stage=".$stage."&clientstage=".$clientstage;
		$ch = curl_init();
		$ch = curl_init($get_url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 

			if ($output===FALSE) {//������,д�������־��
			 $fp=fopen("/var/log/csc/clientstage.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		");
			   fwrite($fp,"transfer  ");
			  fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);

}

else if($clientstage==3)
//˵������Ҫ��ʼ��Ŀ������������ļ�
{
		$stage=$_POST['stage']; 
		$user=$_POST['user'];
   		$fp=fopen("/var/log/csc/clientstage.txt","a");//�ѷ��ص�����д���ļ�
   		fwrite($fp,$clientstage);
		fclose($fp);

       
		
		//��֮ǰ�������������Ŀ���������ip��ַȡ����
		$arr=file("/var/log/csc/checkpass.txt");
		for($i=0;$i<count($arr);$i++)
		{
		   $arr[$i]=trim($arr[$i]);
		}
		
		
		$url = "http://".$arr[1]."/www/server.php" ;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, 1);
		$flag="1";//����
		
		//print_r($arr);
	   
		//��֮ǰ���յ����ļ���Ϣ������
		$arrfilename=file("/var/log/csc/sendfileinfo.txt");
		for($i=0;$i<count($arrfilename);$i++)
		{
		   $arrfilename[$i]=trim($arrfilename[$i]);
		}
		
		//print_r($arrfilename);
		$ff=fopen("/var/log/csc/return.txt","w");//��server�˵ķ�����Ϣд���ļ�
		for($i=0;$i<count($arrfilename);$i++)
		{
				$post_data= array( 
				//"originip"=>$originip,
				"check" => $arr[0],
				"user"=>$user,
				"PublicPath"=>$PublicPath,
				"oldpath"=>$arrfilename[$i],
				"upload" => "@".$arrfilename[$i]
				);
				
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				$output = curl_exec($ch);
				if ($output===FALSE) 
				{//������
					if(file_exists("/var/log/csc/error.txt"))
					{
						$ff=fopen("/var/log/csc/error.txt","w");
						fclose($ff);
					}
					$ff=fopen("/var/log/csc/error.txt","a");
					fwrite($ff,"cURL Error: " . curl_error($ch)."\n");
					fwrite($ff,"cURL Error No.: " .curl_errno($ch)."\n");
					fclose($ff);
					$flag="0";
					break;
				}
					
				//�����ص�ֵд���ļ�log/return.txt������ɾ���ļ�ʱʹ��	
				fwrite($ff,$flag);
				fwrite($ff,"\n");
				fwrite($ff,$output);
			}
		curl_close($ch);
		fclose($ff);
		//�ļ�������ϣ��������Ƿ�ɹ�����Ϣ�����ظ�manage/transfer.php
		
		$url = "http://".$webserverip."/manage/transfer.php";
		
		$ch1 = curl_init();
		curl_setopt($ch1, CURLOPT_URL, $url);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch1, CURLOPT_POST, 1);
		$post_data=array( "stage"=>"5","upload" => "@/var/log/csc/return.txt");
		curl_setopt($ch1,CURLOPT_VERBOSE,1);
		curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch1);
		curl_close($ch1);
		
		
		
		
		
		 
				//�������ļ��Ժ�Ӧ��ɾ�����ص��ļ�------------------------------------------------------------------- 
	 deletefile($arrfilename);	//ɾ�����ش���ɹ����ļ�
} 
//****************************************************************************************
//ɾ�������ļ��ĺ���������ʵ����ͨ����clientoutput�в鿴����ÿ���ļ��ķ�����Ϣ��ֻ�е����post�ɹ�
//����server�˳ɹ��Ľ��ļ�ת����֮�����ɹ���������ɹ��ı����ļ�ɾ��
//�����Ǵ���ļ�����ʵ·��
//****************************************************************************************
 function deletefile($arrfilename)
{
  $data=file("/var/log/csc/return.txt");//���ļ�һ��һ�ж�������
		$count=count($data);
		
		//��output.txt��ֵȥ�޸����ݿ�
		//����һ����ά���飬�ֱ�洢��ͬ���͵�ֵ
		$array=array(
				 "postflag"=>array(),
				 "id"=>array(),
				 "newpath"=>array(),
				 "receiveflag"=>array()
					);
		$filecount=$count/4;
		
		for($i=0;$i<$filecount;$i++)
		{
		$array["postflag"][$i]=current($data);
		next($data);
		$array["id"][$i]=current($data);
		next($data);
		$array["newpath"][$i]=current($data);
		next($data);
		$array["receiveflag"][$i]=current($data);
		next($data);
		
		}
		 
		for($i=0;$i<$filecount;$i++)//ȥ��Դ�ļ������ڷָ���ַ�"\n"
		{
		  // echo "<br>i".$i;
		  $array["postflag"][$i]=trim($array["postflag"][$i]);
		  $array["id"][$i]=trim($array["id"][$i]);
		  $array["newpath"][$i]=trim($array["newpath"][$i]);
		  $array["receiveflag"][$i]=trim($array["receiveflag"][$i]);  
		   
		}
		 
		 
		 for($i=0;$i<$filecount;$i++)//������ͳɹ��ҽ��ճɹ�����ɾ�����ļ��������ϵ��ļ�
		{
		   
		 if($array["postflag"][$i]=='1'&&$array["receiveflag"][$i]=='1')
			  {  if(!file_exists("/var/log/csc/test.txt"))
					  {
					  $fp=fopen("/var/log/csc/test.txt","w");
					  fclose($fp);
					  
					  }
				   $res = unlink($arrfilename[$i]);
				   if($res)
				   {//echo "ɾ���ɹ�"��������ĸ�Ŀ¼Ϊ�գ���Ҳɾ��
						$dirname=dirname($arrfilename[$i]);
						$ff=fopen("/var/log/csc/test.txt","a");
						fwrite($ff,"dirname:".$dirname."\n");
						while(!isEmptyDir($dirname))//˵��Ϊ��Ŀ¼��Ӧ��ɾ��
						{
							@rmdir($dirname);
						 fwrite($ff,"after delete dirname:".dirname($dirname));	
						 $dirname=	dirname($dirname);							
						}
						fclose($ff);
				   }
				   else{
				  // echo "ɾ��ʧ��";
				   }
			  }
			  else{
			//  echo "�ļ�û�д���ɹ�";
			  
			  }
		}
 
}

function   isEmptyDir($path) 
{ 
        $dh=opendir($path); 
        while(false!==($f=readdir($dh))) 
        { 
              if($f!= "."&&$f!="..") 
                    return   true; 
        } 
        return   false; 
}
//---------------------------------------------------------------------------------------------------- 
?>