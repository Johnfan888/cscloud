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
$PublicPath= $c->_get("UserFilePath"); //公共路径
if($clientstage==1)
//说明是第一步应该接受密码，并把密码存储下来
{
    $fp=fopen("/var/log/csc/clientstage.txt","w");//把返回的内容写入文件
    fwrite($fp,$clientstage."\n");
    fclose($fp);
      
	$check=$_GET['password'];
	$stage=$_GET['stage'];
	$targetip=$_GET['targetip'];
	$stage=$stage+1;
	//把密码记录到本地
	$fp=fopen("/var/log/csc/checkpass.txt","w");//把返回的内容写入文件
    fwrite($fp,$check."\n");
	fwrite($fp,$targetip);
    fclose($fp);
	$get_url="http://".$webserverip."/manage/transfer.php?stage=".$stage."&clientstage=".$clientstage;
		$ch = curl_init();
		$ch = curl_init($get_url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 

			if ($output===FALSE) {//出错处理,写入错误日志中
			 $fp=fopen("/var/log/csc/clientstage.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		");
			   fwrite($fp,"transfer  ");
			  fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);
}
else if($clientstage==2)
//说明应该接收文件的信息,并把接收到的文件信息写到sendfileinfo.txt中
{
	$fp=fopen("/var/log/csc/clientstage.txt","a");//把返回的内容写入文件
    fwrite($fp,$clientstage."\n");
    fclose($fp);
   

	$cou=(count($_POST)-2);//一共有几个数组元素
	//echo "<br>file count is ".$cou."<br>";
	//取出前3个，剩下的都是有关所要上传文件的信息
	$stage=$_POST["stage"]+1;
	//print_r($_POST);
	$fp=fopen("/var/log/csc/sendfileinfo.txt","w");//把需要传输的文件的信息写入文件
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

			if ($output===FALSE) {//出错处理,写入错误日志中
			 $fp=fopen("/var/log/csc/clientstage.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		");
			   fwrite($fp,"transfer  ");
			  fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);

}

else if($clientstage==3)
//说明现在要开始给目标服务器传输文件
{
		$stage=$_POST['stage']; 
		$user=$_POST['user'];
   		$fp=fopen("/var/log/csc/clientstage.txt","a");//把返回的内容写入文件
   		fwrite($fp,$clientstage);
		fclose($fp);

       
		
		//把之前传过来的密码和目标服务器的ip地址取出来
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
		$flag="1";//用于
		
		//print_r($arr);
	   
		//把之前接收到的文件信息读出来
		$arrfilename=file("/var/log/csc/sendfileinfo.txt");
		for($i=0;$i<count($arrfilename);$i++)
		{
		   $arrfilename[$i]=trim($arrfilename[$i]);
		}
		
		//print_r($arrfilename);
		$ff=fopen("/var/log/csc/return.txt","w");//将server端的返回信息写进文件
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
				{//出错处理
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
					
				//将返回的值写进文件log/return.txt供后面删除文件时使用	
				fwrite($ff,$flag);
				fwrite($ff,"\n");
				fwrite($ff,$output);
			}
		curl_close($ch);
		fclose($ff);
		//文件传输完毕，将传输是否成功的信息，返回给manage/transfer.php
		
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
		
		
		
		
		
		 
				//传输完文件以后，应该删除本地的文件------------------------------------------------------------------- 
	 deletefile($arrfilename);	//删除本地传输成功的文件
} 
//****************************************************************************************
//删除本地文件的函数，具体实现是通过在clientoutput中查看传输每个文件的返回信息，只有当打包post成功
//和在server端成功的将文件转存了之后才算成功，将传输成功的本地文件删除
//参数是存放文件的真实路径
//****************************************************************************************
 function deletefile($arrfilename)
{
  $data=file("/var/log/csc/return.txt");//把文件一行一行读入数组
		$count=count($data);
		
		//用output.txt的值去修改数据库
		//定义一个四维数组，分别存储不同类型的值
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
		 
		for($i=0;$i<$filecount;$i++)//去掉源文件中用于分割的字符"\n"
		{
		  // echo "<br>i".$i;
		  $array["postflag"][$i]=trim($array["postflag"][$i]);
		  $array["id"][$i]=trim($array["id"][$i]);
		  $array["newpath"][$i]=trim($array["newpath"][$i]);
		  $array["receiveflag"][$i]=trim($array["receiveflag"][$i]);  
		   
		}
		 
		 
		 for($i=0;$i<$filecount;$i++)//如果发送成功且接收成功的则删除本文件服务器上的文件
		{
		   
		 if($array["postflag"][$i]=='1'&&$array["receiveflag"][$i]=='1')
			  {  if(!file_exists("/var/log/csc/test.txt"))
					  {
					  $fp=fopen("/var/log/csc/test.txt","w");
					  fclose($fp);
					  
					  }
				   $res = unlink($arrfilename[$i]);
				   if($res)
				   {//echo "删除成功"，如果它的父目录为空，则也删除
						$dirname=dirname($arrfilename[$i]);
						$ff=fopen("/var/log/csc/test.txt","a");
						fwrite($ff,"dirname:".$dirname."\n");
						while(!isEmptyDir($dirname))//说明为空目录，应该删除
						{
							@rmdir($dirname);
						 fwrite($ff,"after delete dirname:".dirname($dirname));	
						 $dirname=	dirname($dirname);							
						}
						fclose($ff);
				   }
				   else{
				  // echo "删除失败";
				   }
			  }
			  else{
			//  echo "文件没有传输成功";
			  
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