<?php
$stage=$_GET['stage'];
$userid=$_GET['userid'];
if($userid != ""){
	$fp=fopen("/var/log/csc/stage.txt","w");
	fwrite($fp,$userid."\n");
	fclose($fp);
}
if($stage=="")
{
$stage=$_POST['stage'];
}
$user=$_COOKIE['admin']['username'];
require("../include/configure_class.php"); 
$c = new Configuration();//操作密码等配置信息
$c->_construct();		
$c1 = new Configuration();//操作文件迁移的源,目标信息
$c1->configFile="/var/log/csc/transfer_server.txt"; 
$c1->_construct();
$originip=trim($c1->_get("originip")); //源ip
$targetip=trim($c1->_get("targetip")); //目标ip

$configmanagerip=trim($c1->_get("configmanagerip")); 

if($stage==1)//第1步发送密码给目标服务器阶段
{      

		$user=$_GET["user"];
		
		$fp=fopen("/var/log/csc/stage.txt","a"); //不存在
        fwrite($fp,$stage."\n");
		fclose($fp);
			
		//读出配置文件config.txt中有关文件迁移的配置
		$password=$c->_get("Password");
		if($password=="")//说明是用户没有设置密码，则自动生成密码
		{//add your code here
		
		//调用产生密码的函数
		$password=createpass();
		$url="http://".$targetip."/www/server_change_pass.php?password=".rawurlencode($password)."&stage=1";
		$ch = curl_init($url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 
			if ($output===FALSE) {//出错处理,写入错误日志中
			 $fp=fopen("/var/log/csc/stage.txt","a");
			  fwrite($fp,date("Y-m-d H:i:s")."		");
			  fwrite($fp,"cURL1 Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);
			if(!headers_sent())
			{
				echo "服务器连接失败"; 
			}
			exit;
		}
		else//说明用户设置了密码，则在迁移过程中使用该密码
		{//add your code here
		
				//header("HTTP/1.0 404 Not Found" );
		$url="http://".$targetip."/www/server_change_pass.php?password=".rawurlencode($password)."&stage=1";	
		$ch = curl_init($url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 

			if ($output===FALSE) {//出错处理,写入错误日志中
			 $fp=fopen("/var/log/csc/stage.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		");
			  fwrite($fp,"cURL2 Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);
				
				exit;
				//echo $password;
		
		}
			//产生密码的函数
		function createpass()
		{
			$pass="echo12345";
			return $pass;
		}

}
else if($stage==2)//说明给目标服务器发送密码成功,则把密码和目标服务器的ip信息发给源文件服务器
{
    	$fp=fopen("/var/log/csc/stage.txt","a");
        fwrite($fp,$stage."\n");
		fclose($fp);
   
	   $password=$_GET['password'];
	   //echo $password;
	   $clientstage=1;
	 
	    $url = "http://".$originip."/www/client.php?password=".$password."&targetip=".$targetip."&stage=".$stage."&clientstage=".$clientstage;  
		$ch = curl_init($url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 
			if ($output===FALSE) {//出错处理,写入错误日志中
			 $fp=fopen("/var/log/csc/stage.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		");
			  fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);
}
else if($stage==3)//说明源服务器接收密码成功，开始发送文件信息
{
		//require("transfer_strategy.php");
	//	$fileowner=selectuser($originip);//表示按用户迁移时迁移的是哪个用户的文件
		/*if($fileowner=='0')
		{
			$fp=fopen("/var/log/csc/stage.txt","a");
			fwrite($fp,$stage."\n");
			fwrite($fp,"only one user,needn't transfer!"."\n");
			fclose($fp);
			//不需要迁移，返回configer，将数据库的相应字段回滚
				$get_url="http://".$configmanagerip."/configmanager/write_flag.php?flag=3&ip=".$originip;
				$ch = curl_init($get_url) ; 
				$fp=fopen("/var/log/csc/stage.txt","a"); 
				fwrite($fp,$get_url."\n");
				fclose($fp);
				/*curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
				$output = curl_exec($ch) ; 
				if ($output===FALSE) 
				{//出错处理,写入错误日志中
					if(!is_file("/var/log/csc/transfer_log.txt"))
					{
					 $fp=fopen("/var/log/csc/transfer_log.txt","w");
					  fclose($fp);
					}
				}
				  curl_close($ch);

			exit();
		}*/
		//选择到了用户
		$fp=fopen("/var/log/csc/stage.txt","a+");
		$fileowner=trim(fgets($fp));
        fwrite($fp,$stage."\n");
		fclose($fp);
   
//在这里决定要迁移哪些文件，然后将文件的location传给filelocation，如果是多文件，那么传数组可以通过地址栏传送参数也可以通过post传递，地址栏传送时有长度限制1024k，所以做的时候我觉得还是用post方法好一些，
			$clientstage=$_GET['clientstage']+1;//$clientstage=2
			 $url = "http://".$originip."/www/client.php";
			 $post_data1 = array (
		     "stage"=>$stage, //变量值为3
			 "clientstage"=>$clientstage //$clientstage=2
			 );
			   	$post_data2=select($fileowner,$originip); //返回公共路径
			    $cou=count($post_data2); //
			
			if($cou==0)//数组为空，也就是说明没找到合适的文件
			{
				echo "<script language=\"JavaScript\">";
						echo "if(confirm(\"没有选择合适的文件！\"))";
						echo " { location.href=\"updown.php\";}";
						echo " else { location.href=\"updown.php\";}";
						echo "</script>"; 
			
			}
			else
			{
				$array=array();
				for($i=0;$i<$cou;$i++)
				{$array[$i]="file".$i;} //定义数组
				
				$post_data22=array_combine($array,$post_data2);//创建一个数组，让数组的键名为$array，键值为$post_data2   其实是文件和文件路径的对应
				for($i=0;$i<$cou;$i++)
				{
				  $name="file".$i;
				  $post_data22[$name]=trim($post_data22[$name]);//去掉键值中的回车"/n"
				}
				$post_data=array_merge($post_data1,$post_data22);//把两个数组$post_data1,$post_data22合并成一个
				
				
				 $output=Postarray($url,$post_data);//发送一个表单的函数（client.php）
				 
				$arrayy=split("\n",$output);//这个数组中的一个为$stage的值，第二个为$clientstage的值
				
			}

}
else if($stage==4)//说明发送的文件信息已经收到，下面开始让源服务器client.php传输文件
{
			$fp=fopen("/var/log/csc/stage.txt","a+");
			$fileowner=trim(fgets($fp));
			fwrite($fp,$stage."\n");
			fclose($fp);
	    //	$fileowner=selectuser($originip);  //返回用户的id
		$clientstage=$_GET['clientstage'];
		$clientstage=$clientstage+1; //$clientstage=3
		$url = "http://".$originip."/www/client.php"; 
		$post_data = array (
			   "stage"=>$stage,
			   "user"=>$fileowner,
			   "clientstage"=>$clientstage
				);
		  $output=Postarray($url,$post_data);//发送一个表单的函数
	
}
elseif($stage==5)//说明文件传输成功，转到修改数据库信息
{
		$fp=fopen("/var/log/csc/stage.txt","a");
		fwrite($fp,$stage."\n");
		fclose($fp);
	   //接收传输文件是否成功的标记文件
	   if(move_uploaded_file($_FILES['upload']['tmp_name'],"/var/log/csc/output.txt")) 
	   {
	   echo "received";
		 
	   }
	   else{ echo "error";}
		require_once("../include/comment.php");
		require_once("../include/user.class.php");
		$user =&username::getInstance();
		
		$fp=fopen("/var/log/csc/file_transfer_result.html","w");//把迁移文件的结果写入文件中
		$html="<html><head></head><body>";
		fwrite($fp,$html);
	//	fwrite($fp, "<br><a href='../updown.php'>返回用户管理页面！</a>");
		fwrite($fp, "<br><a href='../updown.php'>Return to the user management page</a>");	
		fwrite($fp,"<p>targetip:".$targetip."</p>");
		$data=file("/var/log/csc/output.txt");//把文件一行一行读入数组
		$count=count($data);
 		//test
 		fwrite($fp,$counts);
		//用output.txt的值去修改数据库
		//定义一个四维数组，分别存储不同类型的值
		$array=array(
         "postflag"=>array(),
		 "id"=>array(),
		 "newpath"=>array(),
		 "receiveflag"=>array()
            );
		for($i=0;$i<($count/4);$i++)
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

			$filecount=$count/4;
			for($i=0;$i<$filecount;$i++)//去掉源文件中用于分割的字符"\n"
			{
			  // echo "<br>i".$i;
			  $array["postflag"][$i]=trim($array["postflag"][$i]);
			  $array["id"][$i]=trim($array["id"][$i]);
			  $array["newpath"][$i]=trim($array["newpath"][$i]);
			  $array["receiveflag"][$i]=trim($array["receiveflag"][$i]);  
			   
			}
			if($count%4==0)
			{
				//fwrite($fp,"<p>一共传输的文件数:".$filecount."</p>"); 
				fwrite($fp,"<p>A total number of file transfer:".$filecount."</p>"); 
			}
			else
			{
			   //fwrite($fp,"<p>一共传输的文件数:".floor($filecount)."</p>"); 
			   fwrite($fp,"<p>A total number of file transfer:".floor($filecount)."</p>");
			}
			
			for($i=0;$i<$filecount;$i++)//修改tb_file_location表
			{
			 if($array["postflag"][$i]=='1'&&$array["receiveflag"][$i]=='1')
			  {
			  //存储文件传输的日志信息，表为log_transfer
			  date_default_timezone_set('Asia/Shanghai');
			  $showtime=date("Y-m-d H:i:s");//取当前时间
			 // $sql="select locationpath from tb_file_location where id='".$array["id"][$i]."'";
			 $sql="SELECT file_path FROM T_FileLocation WHERE file_id='".$array["id"][$i]."'";
			  $menu=mysql_fetch_array(mysql_query($sql,$user->Con1));//取得该文件的源路径


			//将该文件的信息插入到迁移的日志文件中			  
			$sql=$showtime.",".$array["id"][$i].",".$originip.",".$targetip.",".$menu["file_path"].",".$array["newpath"][$i]."\n"; 
			$ff=fopen("/var/log/csc/transfer.txt","a");
			fwrite($ff,$sql);
			fclose($sql);
			//读取用户id信息
			$info=file("/var/log/csc/stage.txt");	
			$user_id=$info[0];
			//修改T_UserZone	信息，改变用户的主服务器；
		 	$sql="UPDATE T_UserZone SET server_ip='".$targetip."' WHERE user_id='".$user_id."'";
		  	 mysql_query($sql,$user->Con1);
			//修改tb_file_location的serverip信息
			   $sql="update T_FileLocation set server_ip='".$targetip."' where file_id='".$array["id"][$i]."'";
			    mysql_query($sql,$user->Con1);
			   
			   //做迁移文件后的操作
			 $fp1=fopen("/var/log/csc/fileid.txt","r");  //没有文件的操作？？
			 $fileid=fread($fp1,filesize("/var/log/csc/fileid.txt"));
			 fclose($fp1);
					 if($fileid=="1")
					{//说明是管理员要求迁移文件，则返回信息给管理员
						fwrite($fp,"<p><font color='#FF0000'>文件的id为：".$array["id"][$i]."</font></p>");
						fwrite($fp,"<p>文件的源服务器ip为：".$originip."</p>");
						fwrite($fp,"<p>文件的目标服务器ip为：".$targetip."</p>");
						fwrite($fp,"<p>文件的原路径为：".$menu["locationpath"]."</p>");
						fwrite($fp,"<p>文件的新路径为：".$array["newpath"][$i]."</p>");
						fwrite($fp,"<p>client端是否发送成功：".$array["postflag"][$i]."<通过查看该值可是知道client端发送文件是否成功，如果为1说明post成功，否则post失败!>"."</p>");
						fwrite($fp,"<p>servert端是否接收成功：".$array["receiveflag"][$i]."通过查看该值可是知道server端接收是否成功，如果为1说明成功，否则说明失败!"."</p>");
						fwrite($fp,"<p><font color='#FF0000'>文件传输成功！</font></p>");
						fwrite($fp,"<p><font color='#FF0000'>数据库修改成功！</font></p>");
					}
					else//说明是上传文件中的同名文件迁移，//到相应的文件服务器上去执行存储差量的操作，它应该知道的参数：文件第一版本的id号，文件的存储路径，存储该文件的文件服务器的ip地址
					{
					 //查出版本1的id
						$sql="select * from tb_file_all where name=(select name from tb_file_all where id='".$array["id"][$i]."') order by version asc limit 1";
						$rerere=mysql_query($sql,$user->Con1);
						$mmu=mysql_fetch_array($rerere);
				
						$url=$targetip."/www/backup.php?";
						header("Location:http://".$url."path=".$array["newpath"][$i]."&oldid=".$mmu[id]."&newid=".$array["id"][$i]);
					}
   
			   }
			   else{
			   		echo $array["postflag"][$i];//."通过查看该值可是知道client端发送文件是否成功，如果为1说明post成功，否则post失败!<br>";
					echo $array["receiveflag"][$i];//."通过查看该值可是知道server端接收是否成功，如果为1说明成功，否则说明失败!<br>";
					echo "<br>文件传输失败！";
			   
			   }
			}
			$html="</body></html>";
		    fwrite($fp,$html);
			fclose($fp);
//将传输完成的消息告诉configmanager
			$get_url="http://".$configmanagerip."/configmanager/write_flag.php?flag=2&ip=".$originip;
			$ch = curl_init($get_url) ; 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
			$output = curl_exec($ch) ; 
			if ($output===FALSE) {//出错处理,写入错误日志中
			if(!is_file("/var/log/csc/transfer_log.txt"))
			{
			  $fp=fopen("/var/log/csc/transfer_log.txt","w");
			  fclose($fp);
			}
			 $fp=fopen("/var/log/csc/transfer_log.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		".$configmanagerip." ");
			   fwrite($fp,"transfer_end  ");
			  fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);
			 
		//迁移完成后改变队列的状态；

}






 /****************************************************************************
此函数是自动选择文件的函数,$num是自动选择文件的个数，$originip是源文件服务器的ip地址，此函数返回值是存放
选择的文件的路径信息的一个数组
****************************************************************************/
function select($fileowner,$originip) //传递参数用户id，源IP
{
	require_once("../include/comment.php");
	require_once("../include/user.class.php");
	$user11 =&username::getInstance();
		
	//使用用户名在members中查询用户user_id
	/*$sql="select user_id from members where username='".$fileowner."'"; //不存在members数据库
	$RES=mysql_query($sql,$user11->Con1);
	$MENU=mysql_fetch_array($RES);
	$user_id=$MENU["user_id"];	*/	
	
	
	$post_data2=array();
	
	$sql="select file_id, file_path from T_FileLocation where server_ip='".$originip."' and  user_id='".$fileowner."'";//选择需要迁移的文件，目录不需迁移
	$result=mysql_query($sql,$user11->Con1);
	$i=0;
	$fp=fopen("/var/log/csc/tt.txt","w");
	while($rows=mysql_fetch_assoc($result))
	{
		//分割文件id，获取到文件完整路径
		$path=$rows["file_path"];
		$file_id=$rows["file_id"];
		$arr=str_split($file_id,8);
		$arr0=substr($arr[0], -1);
		$arr1=substr($arr[1], -1);
		$arr2=substr($arr[2], -1);
		$arr3=substr($arr[3], -1);
		$arr0=md5($arr0);
		$arr1=md5($arr1);
		$arr2=md5($arr2);
		$arr3=md5($arr3);
		//$post_data2[$i]=$rows["file_path"].$fileowner."/";
		$post_data2[$i] = "{$path}{$fileowner}/{$arr0}/{$arr1}/{$arr2}/{$arr3}/{$file_id}";
		fwrite($fp,$post_data2[$i]."\n");
		$i++;
	}
	fclose($fp);
	return $post_data2;
	
}
/*******************************************************************************
发送一个表单的函数
参数$post_data为想要post的数据组成的数组
返回值为递交后服务器端的返回值
*******************************************************************************/
function Postarray($url,$post_data)
{

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$output=curl_exec($ch);
	curl_close($ch);
	return $output;
}

//选择需要迁移文件的用户
function selectuser($originip)
{
	require_once("../include/comment.php"); 
	require_once("../include/user.class.php"); 

	$tt =&username::getInstance();
		$sql="select user_id from T_UserZone where server_ip='".$originip."' order by used_size desc limit 1"; //查到的为用户id
		$result=mysql_query($sql,$tt->Con1);
		$menu=mysql_fetch_array($result);
		$fp=fopen("username.txt","w");
		fwrite($fp,$menu["user_id"]);
		fclose($fp);

}




?>
