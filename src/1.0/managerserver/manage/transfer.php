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
$c = new Configuration();//���������������Ϣ
$c->_construct();		
$c1 = new Configuration();//�����ļ�Ǩ�Ƶ�Դ,Ŀ����Ϣ
$c1->configFile="/var/log/csc/transfer_server.txt"; 
$c1->_construct();
$originip=trim($c1->_get("originip")); //Դip
$targetip=trim($c1->_get("targetip")); //Ŀ��ip

$configmanagerip=trim($c1->_get("configmanagerip")); 

if($stage==1)//��1�����������Ŀ��������׶�
{      

		$user=$_GET["user"];
		
		$fp=fopen("/var/log/csc/stage.txt","a"); //������
        fwrite($fp,$stage."\n");
		fclose($fp);
			
		//���������ļ�config.txt���й��ļ�Ǩ�Ƶ�����
		$password=$c->_get("Password");
		if($password=="")//˵�����û�û���������룬���Զ���������
		{//add your code here
		
		//���ò�������ĺ���
		$password=createpass();
		$url="http://".$targetip."/www/server_change_pass.php?password=".rawurlencode($password)."&stage=1";
		$ch = curl_init($url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 
			if ($output===FALSE) {//������,д�������־��
			 $fp=fopen("/var/log/csc/stage.txt","a");
			  fwrite($fp,date("Y-m-d H:i:s")."		");
			  fwrite($fp,"cURL1 Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);
			if(!headers_sent())
			{
				echo "����������ʧ��"; 
			}
			exit;
		}
		else//˵���û����������룬����Ǩ�ƹ�����ʹ�ø�����
		{//add your code here
		
				//header("HTTP/1.0 404 Not Found" );
		$url="http://".$targetip."/www/server_change_pass.php?password=".rawurlencode($password)."&stage=1";	
		$ch = curl_init($url) ; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		$output = curl_exec($ch) ; 

			if ($output===FALSE) {//������,д�������־��
			 $fp=fopen("/var/log/csc/stage.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		");
			  fwrite($fp,"cURL2 Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);
				
				exit;
				//echo $password;
		
		}
			//��������ĺ���
		function createpass()
		{
			$pass="echo12345";
			return $pass;
		}

}
else if($stage==2)//˵����Ŀ���������������ɹ�,��������Ŀ���������ip��Ϣ����Դ�ļ�������
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
			if ($output===FALSE) {//������,д�������־��
			 $fp=fopen("/var/log/csc/stage.txt","a");
			   fwrite($fp,date("Y-m-d H:i:s")."		");
			  fwrite($fp,"cURL Error:".curl_error($ch)."   ");
			  fwrite($fp,"cURL Error No.:".curl_error($ch)."\n");
			  } 
			   curl_close($ch);
}
else if($stage==3)//˵��Դ��������������ɹ�����ʼ�����ļ���Ϣ
{
		//require("transfer_strategy.php");
	//	$fileowner=selectuser($originip);//��ʾ���û�Ǩ��ʱǨ�Ƶ����ĸ��û����ļ�
		/*if($fileowner=='0')
		{
			$fp=fopen("/var/log/csc/stage.txt","a");
			fwrite($fp,$stage."\n");
			fwrite($fp,"only one user,needn't transfer!"."\n");
			fclose($fp);
			//����ҪǨ�ƣ�����configer�������ݿ����Ӧ�ֶλع�
				$get_url="http://".$configmanagerip."/configmanager/write_flag.php?flag=3&ip=".$originip;
				$ch = curl_init($get_url) ; 
				$fp=fopen("/var/log/csc/stage.txt","a"); 
				fwrite($fp,$get_url."\n");
				fclose($fp);
				/*curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
				$output = curl_exec($ch) ; 
				if ($output===FALSE) 
				{//������,д�������־��
					if(!is_file("/var/log/csc/transfer_log.txt"))
					{
					 $fp=fopen("/var/log/csc/transfer_log.txt","w");
					  fclose($fp);
					}
				}
				  curl_close($ch);

			exit();
		}*/
		//ѡ�����û�
		$fp=fopen("/var/log/csc/stage.txt","a+");
		$fileowner=trim(fgets($fp));
        fwrite($fp,$stage."\n");
		fclose($fp);
   
//���������ҪǨ����Щ�ļ���Ȼ���ļ���location����filelocation������Ƕ��ļ�����ô���������ͨ����ַ�����Ͳ���Ҳ����ͨ��post���ݣ���ַ������ʱ�г�������1024k����������ʱ���Ҿ��û�����post������һЩ��
			$clientstage=$_GET['clientstage']+1;//$clientstage=2
			 $url = "http://".$originip."/www/client.php";
			 $post_data1 = array (
		     "stage"=>$stage, //����ֵΪ3
			 "clientstage"=>$clientstage //$clientstage=2
			 );
			   	$post_data2=select($fileowner,$originip); //���ع���·��
			    $cou=count($post_data2); //
			
			if($cou==0)//����Ϊ�գ�Ҳ����˵��û�ҵ����ʵ��ļ�
			{
				echo "<script language=\"JavaScript\">";
						echo "if(confirm(\"û��ѡ����ʵ��ļ���\"))";
						echo " { location.href=\"updown.php\";}";
						echo " else { location.href=\"updown.php\";}";
						echo "</script>"; 
			
			}
			else
			{
				$array=array();
				for($i=0;$i<$cou;$i++)
				{$array[$i]="file".$i;} //��������
				
				$post_data22=array_combine($array,$post_data2);//����һ�����飬������ļ���Ϊ$array����ֵΪ$post_data2   ��ʵ���ļ����ļ�·���Ķ�Ӧ
				for($i=0;$i<$cou;$i++)
				{
				  $name="file".$i;
				  $post_data22[$name]=trim($post_data22[$name]);//ȥ����ֵ�еĻس�"/n"
				}
				$post_data=array_merge($post_data1,$post_data22);//����������$post_data1,$post_data22�ϲ���һ��
				
				
				 $output=Postarray($url,$post_data);//����һ�����ĺ�����client.php��
				 
				$arrayy=split("\n",$output);//��������е�һ��Ϊ$stage��ֵ���ڶ���Ϊ$clientstage��ֵ
				
			}

}
else if($stage==4)//˵�����͵��ļ���Ϣ�Ѿ��յ������濪ʼ��Դ������client.php�����ļ�
{
			$fp=fopen("/var/log/csc/stage.txt","a+");
			$fileowner=trim(fgets($fp));
			fwrite($fp,$stage."\n");
			fclose($fp);
	    //	$fileowner=selectuser($originip);  //�����û���id
		$clientstage=$_GET['clientstage'];
		$clientstage=$clientstage+1; //$clientstage=3
		$url = "http://".$originip."/www/client.php"; 
		$post_data = array (
			   "stage"=>$stage,
			   "user"=>$fileowner,
			   "clientstage"=>$clientstage
				);
		  $output=Postarray($url,$post_data);//����һ�����ĺ���
	
}
elseif($stage==5)//˵���ļ�����ɹ���ת���޸����ݿ���Ϣ
{
		$fp=fopen("/var/log/csc/stage.txt","a");
		fwrite($fp,$stage."\n");
		fclose($fp);
	   //���մ����ļ��Ƿ�ɹ��ı���ļ�
	   if(move_uploaded_file($_FILES['upload']['tmp_name'],"/var/log/csc/output.txt")) 
	   {
	   echo "received";
		 
	   }
	   else{ echo "error";}
		require_once("../include/comment.php");
		require_once("../include/user.class.php");
		$user =&username::getInstance();
		
		$fp=fopen("/var/log/csc/file_transfer_result.html","w");//��Ǩ���ļ��Ľ��д���ļ���
		$html="<html><head></head><body>";
		fwrite($fp,$html);
	//	fwrite($fp, "<br><a href='../updown.php'>�����û�����ҳ�棡</a>");
		fwrite($fp, "<br><a href='../updown.php'>Return to the user management page</a>");	
		fwrite($fp,"<p>targetip:".$targetip."</p>");
		$data=file("/var/log/csc/output.txt");//���ļ�һ��һ�ж�������
		$count=count($data);
 		//test
 		fwrite($fp,$counts);
		//��output.txt��ֵȥ�޸����ݿ�
		//����һ����ά���飬�ֱ�洢��ͬ���͵�ֵ
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
			for($i=0;$i<$filecount;$i++)//ȥ��Դ�ļ������ڷָ���ַ�"\n"
			{
			  // echo "<br>i".$i;
			  $array["postflag"][$i]=trim($array["postflag"][$i]);
			  $array["id"][$i]=trim($array["id"][$i]);
			  $array["newpath"][$i]=trim($array["newpath"][$i]);
			  $array["receiveflag"][$i]=trim($array["receiveflag"][$i]);  
			   
			}
			if($count%4==0)
			{
				//fwrite($fp,"<p>һ��������ļ���:".$filecount."</p>"); 
				fwrite($fp,"<p>A total number of file transfer:".$filecount."</p>"); 
			}
			else
			{
			   //fwrite($fp,"<p>һ��������ļ���:".floor($filecount)."</p>"); 
			   fwrite($fp,"<p>A total number of file transfer:".floor($filecount)."</p>");
			}
			
			for($i=0;$i<$filecount;$i++)//�޸�tb_file_location��
			{
			 if($array["postflag"][$i]=='1'&&$array["receiveflag"][$i]=='1')
			  {
			  //�洢�ļ��������־��Ϣ����Ϊlog_transfer
			  date_default_timezone_set('Asia/Shanghai');
			  $showtime=date("Y-m-d H:i:s");//ȡ��ǰʱ��
			 // $sql="select locationpath from tb_file_location where id='".$array["id"][$i]."'";
			 $sql="SELECT file_path FROM T_FileLocation WHERE file_id='".$array["id"][$i]."'";
			  $menu=mysql_fetch_array(mysql_query($sql,$user->Con1));//ȡ�ø��ļ���Դ·��


			//�����ļ�����Ϣ���뵽Ǩ�Ƶ���־�ļ���			  
			$sql=$showtime.",".$array["id"][$i].",".$originip.",".$targetip.",".$menu["file_path"].",".$array["newpath"][$i]."\n"; 
			$ff=fopen("/var/log/csc/transfer.txt","a");
			fwrite($ff,$sql);
			fclose($sql);
			//��ȡ�û�id��Ϣ
			$info=file("/var/log/csc/stage.txt");	
			$user_id=$info[0];
			//�޸�T_UserZone	��Ϣ���ı��û�������������
		 	$sql="UPDATE T_UserZone SET server_ip='".$targetip."' WHERE user_id='".$user_id."'";
		  	 mysql_query($sql,$user->Con1);
			//�޸�tb_file_location��serverip��Ϣ
			   $sql="update T_FileLocation set server_ip='".$targetip."' where file_id='".$array["id"][$i]."'";
			    mysql_query($sql,$user->Con1);
			   
			   //��Ǩ���ļ���Ĳ���
			 $fp1=fopen("/var/log/csc/fileid.txt","r");  //û���ļ��Ĳ�������
			 $fileid=fread($fp1,filesize("/var/log/csc/fileid.txt"));
			 fclose($fp1);
					 if($fileid=="1")
					{//˵���ǹ���ԱҪ��Ǩ���ļ����򷵻���Ϣ������Ա
						fwrite($fp,"<p><font color='#FF0000'>�ļ���idΪ��".$array["id"][$i]."</font></p>");
						fwrite($fp,"<p>�ļ���Դ������ipΪ��".$originip."</p>");
						fwrite($fp,"<p>�ļ���Ŀ�������ipΪ��".$targetip."</p>");
						fwrite($fp,"<p>�ļ���ԭ·��Ϊ��".$menu["locationpath"]."</p>");
						fwrite($fp,"<p>�ļ�����·��Ϊ��".$array["newpath"][$i]."</p>");
						fwrite($fp,"<p>client���Ƿ��ͳɹ���".$array["postflag"][$i]."<ͨ���鿴��ֵ����֪��client�˷����ļ��Ƿ�ɹ������Ϊ1˵��post�ɹ�������postʧ��!>"."</p>");
						fwrite($fp,"<p>servert���Ƿ���ճɹ���".$array["receiveflag"][$i]."ͨ���鿴��ֵ����֪��server�˽����Ƿ�ɹ������Ϊ1˵���ɹ�������˵��ʧ��!"."</p>");
						fwrite($fp,"<p><font color='#FF0000'>�ļ�����ɹ���</font></p>");
						fwrite($fp,"<p><font color='#FF0000'>���ݿ��޸ĳɹ���</font></p>");
					}
					else//˵�����ϴ��ļ��е�ͬ���ļ�Ǩ�ƣ�//����Ӧ���ļ���������ȥִ�д洢�����Ĳ�������Ӧ��֪���Ĳ������ļ���һ�汾��id�ţ��ļ��Ĵ洢·�����洢���ļ����ļ���������ip��ַ
					{
					 //����汾1��id
						$sql="select * from tb_file_all where name=(select name from tb_file_all where id='".$array["id"][$i]."') order by version asc limit 1";
						$rerere=mysql_query($sql,$user->Con1);
						$mmu=mysql_fetch_array($rerere);
				
						$url=$targetip."/www/backup.php?";
						header("Location:http://".$url."path=".$array["newpath"][$i]."&oldid=".$mmu[id]."&newid=".$array["id"][$i]);
					}
   
			   }
			   else{
			   		echo $array["postflag"][$i];//."ͨ���鿴��ֵ����֪��client�˷����ļ��Ƿ�ɹ������Ϊ1˵��post�ɹ�������postʧ��!<br>";
					echo $array["receiveflag"][$i];//."ͨ���鿴��ֵ����֪��server�˽����Ƿ�ɹ������Ϊ1˵���ɹ�������˵��ʧ��!<br>";
					echo "<br>�ļ�����ʧ�ܣ�";
			   
			   }
			}
			$html="</body></html>";
		    fwrite($fp,$html);
			fclose($fp);
//��������ɵ���Ϣ����configmanager
			$get_url="http://".$configmanagerip."/configmanager/write_flag.php?flag=2&ip=".$originip;
			$ch = curl_init($get_url) ; 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
			$output = curl_exec($ch) ; 
			if ($output===FALSE) {//������,д�������־��
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
			 
		//Ǩ����ɺ�ı���е�״̬��

}






 /****************************************************************************
�˺������Զ�ѡ���ļ��ĺ���,$num���Զ�ѡ���ļ��ĸ�����$originip��Դ�ļ���������ip��ַ���˺�������ֵ�Ǵ��
ѡ����ļ���·����Ϣ��һ������
****************************************************************************/
function select($fileowner,$originip) //���ݲ����û�id��ԴIP
{
	require_once("../include/comment.php");
	require_once("../include/user.class.php");
	$user11 =&username::getInstance();
		
	//ʹ���û�����members�в�ѯ�û�user_id
	/*$sql="select user_id from members where username='".$fileowner."'"; //������members���ݿ�
	$RES=mysql_query($sql,$user11->Con1);
	$MENU=mysql_fetch_array($RES);
	$user_id=$MENU["user_id"];	*/	
	
	
	$post_data2=array();
	
	$sql="select file_id, file_path from T_FileLocation where server_ip='".$originip."' and  user_id='".$fileowner."'";//ѡ����ҪǨ�Ƶ��ļ���Ŀ¼����Ǩ��
	$result=mysql_query($sql,$user11->Con1);
	$i=0;
	$fp=fopen("/var/log/csc/tt.txt","w");
	while($rows=mysql_fetch_assoc($result))
	{
		//�ָ��ļ�id����ȡ���ļ�����·��
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
����һ�����ĺ���
����$post_dataΪ��Ҫpost��������ɵ�����
����ֵΪ�ݽ���������˵ķ���ֵ
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

//ѡ����ҪǨ���ļ����û�
function selectuser($originip)
{
	require_once("../include/comment.php"); 
	require_once("../include/user.class.php"); 

	$tt =&username::getInstance();
		$sql="select user_id from T_UserZone where server_ip='".$originip."' order by used_size desc limit 1"; //�鵽��Ϊ�û�id
		$result=mysql_query($sql,$tt->Con1);
		$menu=mysql_fetch_array($result);
		$fp=fopen("username.txt","w");
		fwrite($fp,$menu["user_id"]);
		fclose($fp);

}




?>
