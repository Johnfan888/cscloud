 <?php
if(!$_COOKIE['admin']['user_id']){
	echo "δ��½";
	exit();
}
?>
<html>             
  <head> 
  <meta http-equiv="Content-Type" content="text/html; charset=gbk" />    
  
  <link rel='stylesheet' type='text/css' href='css/private.css'>   
  <script   language="JavaScript"   src="TreeMenu.js"></script>             
  </head>             
  <body>             
  <table width="90%" border="1" cellspacing="0" cellpadding="0" align="center" class=Navi>
    <tr>
      <td width="30%">
<?php  
require("../include/comment.php");
require("../include/user.class.php");
require("./configure_class.php");
$c = new Configuration();
$c->_construct();
$order_num=$c->_get("Cachecount");
$user =&username::getInstance();
 $createtime=$_POST['createtime'];
 if(isset($_GET["createtime"]))
 {
 $createtime=$_GET["createtime"];
 }

if($_COOKIE['admin']['username'])
{
     $username=$_COOKIE['admin']['username'];
}
else
{
   $username=$_GET['user'];
   setcookie("admin[username]", $username);
}
	
	set_time_limit(0);
	//������������             
	$GLOBALS["ID"]   =1;   //�������������˵���ID��             
	$layer=1;   //�������ٵ�ǰ�˵��ļ���    
     if(!isset($_GET["version_flag"])) 
	{
	$_GET["version_flag"]=0;
	} 
	  //��ȡ�û���user_id 
	$sql="select user_id from members where username='".$username."'";
	$res=mysql_query($sql,$user->Con1);
	$array=mysql_fetch_array($res);
	$user_id=$array["user_id"];        
	$sql="select   *  ,count(*)  from   tb_file_all   where   createtime like '%".$createtime."%' and user_id=(select user_id from members where username='".$_COOKIE['admin']['username']."')  group by parent_id,name";
	$result=mysql_query($sql,$user->Con1);
	echo  "<br>";       
	//���һ���˵�������ʼ�˵�����ʾ   
	if(!isset($_GET["version_flag"])) 
	{
	$version_flag=0;
	$getname="";
	$menu_flag=0;
	}
	else
	{
	$menu_flag=1;
	$getname=$_GET["name"];
	$version_flag=$_GET["version_flag"];
	$getpid=$_GET["pid"];
	}           
	if(mysql_num_rows($result)>0)
	{
	  ShowTreeMenu($user->Con1,$result,$layer,$user_id,$_GET["version_flag"],$createtime,$getname,$getpid,$menu_flag,$ID);
	}             
	else
	{
	echo "û����Ҫ�ҵ��ļ���" ;
	}   
            
  //=============================================             
  //��ʾ���Ͳ˵�����   ShowTreeMenu($con,$result,$layer)             
  //$con�����ݿ�����             
  //$result����Ҫ��ʾ�Ĳ˵���¼��             
  //layer����Ҫ��ʾ�Ĳ˵��ļ���             
  //=============================================             
	function   ShowTreeMenu($Con,$result,$layer,$user_id,$version_flag,$createtime,$getname,$getpid,$menu_flag)                     
{             
		//ȡ����Ҫ��ʾ�Ĳ˵�����Ŀ��             
		$numrows=mysql_num_rows($result);             
		//��ʼ��ʾ�˵���ÿ���Ӳ˵�����һ���������ʾ             
		echo   "<table   cellpadding='0'   cellspacing='0'   border='0'>";             
		echo "<tr>
		<td width='5%' align='center'></td>
		<td width='30%'>filename</td>
		<td width='10%' align='center'>size(MB)</td>
		<td width='14%'align='center'>operation_time</td>
		<td width='3%' align='center'>version_number</td>
		<td width='10%' align='center'>parentdir</td>
		</tr>";			
		  for($rows=0;$rows<$numrows;$rows++)             
		  {             
			  //����ǰ�˵���Ŀ�����ݵ�������             
		         $menu=mysql_fetch_array($result);  
				if($menu["count(*)"]==1)//ֻ��һ���汾����������ļ���������Ŀ¼
				{  
						echo   "<tr>";             
						//����ò˵���Ŀ��Ŀ¼�������JavaScript   onClick���             
						if($menu["filetype"]=='1') //��Ŀ¼�Ĳ���          
						{    
							//��ʾĿ¼ͼ��         
							echo   "<td align='center'><img   src='folder.gif'   border='0'></td>";             				echo   "<td class='Menu' onClick=\"Querydir('".$menu["id"]."');\">"; 
							
							$sql="select * from tb_file_location where id='".$menu["id"]."'";
							$res=mysql_query($sql,$Con);
							$menu11=mysql_fetch_array($res);
							echo "<".$menu11["serverip"].">";
							//��ʾĿ¼��
							echo   $menu["name"];
							//��ʾ��Ŀ¼�Ĳ���
							echo " <br><a href='query.php?id=$menu[id]'>[��]</a>";					
							echo " <a href='delconfirm.php?dir=$menu11[locationpath]&id=$menu[id]&name=$menu[name]&serverip=$menu11[serverip]'>[ɾ��]</a>";						
							echo  "<a href='rename.php?name=$menu[name]&id=$menu[id]'>[����]</a>";       					echo "</td>";
							
							echo "<td></td>"; 
	
						}             
						else //���ļ��Ĳ���      
						{ 
							//��ʾ�ļ�ͼ��
							echo   "<td align='center'><img	src='file.gif'   border='0'></td>";             
							echo   "<td class='Menu' >";     
							$sql="select * from tb_file_location where id='".$menu["id"]."'";
							$res=mysql_query($sql,$Con);
							$menu11=mysql_fetch_array($res);
							echo "<".$menu11["serverip"].">";
							//��ʾ���ļ��Ĳ���
							echo   "<a href='downloadfile.php?dir=".$menu11['locationpath']."&id=".$menu['id']."&name=".$menu['name']."&serverip=".$menu11['serverip']."&replicaip=".$menu11['replicaip']."&replicalocation=".$menu11['replicalocation']."'>".$menu['name']."</a>"; 
							echo " </br><a href='delfileconfirm.php?dir=".$menu11['locationpath']."&id=".$menu['id']."&name=".$menu['name']."&serverip=".$menu11['serverip']."'>[ɾ��]</a>";
							echo  "<a href='rename.php?name=".$menu['name']."&id=".$menu['id']."'>[����]</a>";   					echo "</td>";
							//��ʾ�ļ���С
							$menu['size']=$menu['size']/1000000;
							echo "<td align='center'>".round($menu['size'],2)."</td>"; 	
						}             
							 //��ʾ�޸�ʱ��
							  echo "<td align='center'>".$menu['modifytime']."</td>";
							  //��ʾ�汾��
							  echo "<td align='center'>".$menu['version']."</td>";
							  //��ʾ��Ŀ¼
							if($menu['parent_id']=='0')//���û�и�Ŀ¼��Ĭ��Ϊ��Ŀ¼"/"
							{
								echo "<td align='center'>";
								$parent_name="/";
							}
							else{
							echo   "<td class='Menu' align='center' onClick=\"Querydir('".$menu['parent_id']."');\"><font color='#0033FF'><strong>"; 
							$parent_name=$menu['parent_name'];
							}
							
							echo $parent_name;
							echo "</strong></font></td>";                      
							echo   "</tr>"; 
						  
		         }    
				 else  //˵���ж���汾,��ض����ļ�
				 {	   
				 //----------------------------------------------------------------
					 	 $sql="select * from tb_file_all where name='".$menu['name']."' and user_id='".$user_id."' and parent_id='".$menu['parent_id']."'";//��汾Ӧ����tb_file_all�в�
					 $rere=mysql_query($sql,$Con);
					 //��ʾͼ����ļ���
					if($menu_flag==0)
					{//˵���ǵ�һ�η��ʸ�ҳ
						 echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='createtimequery.php?version_flag=1&name=".$menu['name']."&pid=".$menu['parent_id']."&createtime=".$createtime."'>��ϸ��Ϣ</a></td>";  
					}
					else
					{//˵����ͨ�������ϸ��Ϣ�����ʵĸ�ҳ
						 if(($version_flag=='0')&&($menu['name']==$getname)&&($menu['parent_id']==$getpid))
						 {//չ��
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='createtimequery.php?version_flag=1&name=".$menu['name']."&pid=".$menu['parent_id']."&createtime=".$createtime."'>��ϸ��Ϣ</a></td>";  
						 }
						 else if(($version_flag=='1')&&($menu['name']==$getname)&&($menu['parent_id']==$getpid))
						 {//�ر�
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='createtimequery.php?version_flag=0&name=".$menu['name']."&pid=".$menu['parent_id']."&createtime=".$createtime."'>��ϸ��Ϣ</a></td>";  
						 }else{//������
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='createtimequery.php?version_flag=1&name=".$menu['name']."&pid=".$menu['parent_id']."&createtime=".$createtime."'>��ϸ��Ϣ</a></td>";  
						}
					}
					 
					echo"<td></td>";//size
					echo"<td></td>";//modifytime
					echo"<td align='center'>".$menu["count(*)"]."</td><td></td></tr>";//version
					if(($version_flag=='1')&&($menu['name']==$getname)&&($menu['parent_id']==$getpid))//��ʾ��Ҫչ����汾�ļ�����ϸ��Ϣ
					{ 
							$numnum=mysql_num_rows($rere);
							for($rows1=0;$rows1<$numnum;$rows1++) 
							{
									$me=mysql_fetch_array($rere);
									echo   "<tr>";       
									echo   "<td></td>";             
									echo   "<td class='Menu'>";     
									$sql="select * from tb_file_location where id='".$me['id']."'";
									$res=mysql_query($sql,$Con);
									$me11=mysql_fetch_array($res);
									echo "<".$me11['serverip'].">";
									echo   "<a href='downloadfile.php?dir=".$me11['locationpath']."&id=".$me['id']."&name=".$me['name']."&serverip=".$me11['serverip']."&replicaip=".$me11['replicaip']."&replicalocation=".$me11['replicalocation']."'>"."�汾".$me['version']."</a>"; 
									echo " </br><a href='delfileconfirm.php?dir=".$me11['locationpath']."&id=".$me['id']."&name=".$me['name']."&serverip=".$me11['serverip']."'>[ɾ��]</a>";
									
									echo "</td>";
									echo "<td align='center'>".round($me['size']/1000000,2)."</td>";
									echo "<td align='center'>".$me['modifytime']."</td>";
									echo "<td align='center'></td>";
									
									$sql="select * from tb_file_all  where id='".$me['parent_id']."'";  
									$res_parent=mysql_query($sql,$Con);
									$menu_parent=mysql_fetch_array($res_parent);
									if($menu['parent_id']=='0')//���û�и�Ŀ¼��Ĭ��Ϊ��Ŀ¼"/"
									{
										echo "<td align='center'>";
										$parent_name="/";
									}
									else{
										echo   "<td class='Menu' align='center'  onClick=\"Querydir('".$menu['parent_id']."');\"><font color='#0033FF'><strong>"; 
										$parent_name=$menu_parent['name'];
									}
									echo $parent_name;
									echo "</strong></font></td>";                       
									echo   "</tr>"; 
							}
						}
				 }     
						 	 
		  }             
		  echo   "</table>";             
 } 
 
	
  ?>
</td>
<td width="30%" align="center">
<?php
echo"<a href='updown.php'>������ҳ</a><br>";
?>
</td>
</tr>
</table>
</body>             
</html> 
