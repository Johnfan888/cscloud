 <?php
if(!$_COOKIE['admin']['user_id']){
	echo "未登陆";
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
	//基本变量设置             
	$GLOBALS["ID"]   =1;   //用来跟踪下拉菜单的ID号             
	$layer=1;   //用来跟踪当前菜单的级数    
     if(!isset($_GET["version_flag"])) 
	{
	$_GET["version_flag"]=0;
	} 
	  //获取用户的user_id 
	$sql="select user_id from members where username='".$username."'";
	$res=mysql_query($sql,$user->Con1);
	$array=mysql_fetch_array($res);
	$user_id=$array["user_id"];        
	$sql="select   *  ,count(*)  from   tb_file_all   where   createtime like '%".$createtime."%' and user_id=(select user_id from members where username='".$_COOKIE['admin']['username']."')  group by parent_id,name";
	$result=mysql_query($sql,$user->Con1);
	echo  "<br>";       
	//如果一级菜单存在则开始菜单的显示   
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
	echo "没有您要找的文件！" ;
	}   
            
  //=============================================             
  //显示树型菜单函数   ShowTreeMenu($con,$result,$layer)             
  //$con：数据库连接             
  //$result：需要显示的菜单记录集             
  //layer：需要显示的菜单的级数             
  //=============================================             
	function   ShowTreeMenu($Con,$result,$layer,$user_id,$version_flag,$createtime,$getname,$getpid,$menu_flag)                     
{             
		//取得需要显示的菜单的项目数             
		$numrows=mysql_num_rows($result);             
		//开始显示菜单，每个子菜单都用一个表格来表示             
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
			  //将当前菜单项目的内容导入数组             
		         $menu=mysql_fetch_array($result);  
				if($menu["count(*)"]==1)//只有一个版本，则可能是文件，可能是目录
				{  
						echo   "<tr>";             
						//如果该菜单项目是目录，则添加JavaScript   onClick语句             
						if($menu["filetype"]=='1') //对目录的操作          
						{    
							//显示目录图标         
							echo   "<td align='center'><img   src='folder.gif'   border='0'></td>";             				echo   "<td class='Menu' onClick=\"Querydir('".$menu["id"]."');\">"; 
							
							$sql="select * from tb_file_location where id='".$menu["id"]."'";
							$res=mysql_query($sql,$Con);
							$menu11=mysql_fetch_array($res);
							echo "<".$menu11["serverip"].">";
							//显示目录名
							echo   $menu["name"];
							//显示对目录的操作
							echo " <br><a href='query.php?id=$menu[id]'>[打开]</a>";					
							echo " <a href='delconfirm.php?dir=$menu11[locationpath]&id=$menu[id]&name=$menu[name]&serverip=$menu11[serverip]'>[删除]</a>";						
							echo  "<a href='rename.php?name=$menu[name]&id=$menu[id]'>[改名]</a>";       					echo "</td>";
							
							echo "<td></td>"; 
	
						}             
						else //对文件的操作      
						{ 
							//显示文件图标
							echo   "<td align='center'><img	src='file.gif'   border='0'></td>";             
							echo   "<td class='Menu' >";     
							$sql="select * from tb_file_location where id='".$menu["id"]."'";
							$res=mysql_query($sql,$Con);
							$menu11=mysql_fetch_array($res);
							echo "<".$menu11["serverip"].">";
							//显示对文件的操作
							echo   "<a href='downloadfile.php?dir=".$menu11['locationpath']."&id=".$menu['id']."&name=".$menu['name']."&serverip=".$menu11['serverip']."&replicaip=".$menu11['replicaip']."&replicalocation=".$menu11['replicalocation']."'>".$menu['name']."</a>"; 
							echo " </br><a href='delfileconfirm.php?dir=".$menu11['locationpath']."&id=".$menu['id']."&name=".$menu['name']."&serverip=".$menu11['serverip']."'>[删除]</a>";
							echo  "<a href='rename.php?name=".$menu['name']."&id=".$menu['id']."'>[改名]</a>";   					echo "</td>";
							//显示文件大小
							$menu['size']=$menu['size']/1000000;
							echo "<td align='center'>".round($menu['size'],2)."</td>"; 	
						}             
							 //显示修改时间
							  echo "<td align='center'>".$menu['modifytime']."</td>";
							  //显示版本数
							  echo "<td align='center'>".$menu['version']."</td>";
							  //显示父目录
							if($menu['parent_id']=='0')//如果没有父目录就默认为根目录"/"
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
				 else  //说明有多个版本,则必定是文件
				 {	   
				 //----------------------------------------------------------------
					 	 $sql="select * from tb_file_all where name='".$menu['name']."' and user_id='".$user_id."' and parent_id='".$menu['parent_id']."'";//多版本应该在tb_file_all中查
					 $rere=mysql_query($sql,$Con);
					 //显示图标和文件名
					if($menu_flag==0)
					{//说明是第一次访问该页
						 echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='createtimequery.php?version_flag=1&name=".$menu['name']."&pid=".$menu['parent_id']."&createtime=".$createtime."'>详细信息</a></td>";  
					}
					else
					{//说明是通过点击详细信息而访问的该页
						 if(($version_flag=='0')&&($menu['name']==$getname)&&($menu['parent_id']==$getpid))
						 {//展开
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='createtimequery.php?version_flag=1&name=".$menu['name']."&pid=".$menu['parent_id']."&createtime=".$createtime."'>详细信息</a></td>";  
						 }
						 else if(($version_flag=='1')&&($menu['name']==$getname)&&($menu['parent_id']==$getpid))
						 {//关闭
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='createtimequery.php?version_flag=0&name=".$menu['name']."&pid=".$menu['parent_id']."&createtime=".$createtime."'>详细信息</a></td>";  
						 }else{//其他的
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='createtimequery.php?version_flag=1&name=".$menu['name']."&pid=".$menu['parent_id']."&createtime=".$createtime."'>详细信息</a></td>";  
						}
					}
					 
					echo"<td></td>";//size
					echo"<td></td>";//modifytime
					echo"<td align='center'>".$menu["count(*)"]."</td><td></td></tr>";//version
					if(($version_flag=='1')&&($menu['name']==$getname)&&($menu['parent_id']==$getpid))//表示需要展开多版本文件的详细信息
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
									echo   "<a href='downloadfile.php?dir=".$me11['locationpath']."&id=".$me['id']."&name=".$me['name']."&serverip=".$me11['serverip']."&replicaip=".$me11['replicaip']."&replicalocation=".$me11['replicalocation']."'>"."版本".$me['version']."</a>"; 
									echo " </br><a href='delfileconfirm.php?dir=".$me11['locationpath']."&id=".$me['id']."&name=".$me['name']."&serverip=".$me11['serverip']."'>[删除]</a>";
									
									echo "</td>";
									echo "<td align='center'>".round($me['size']/1000000,2)."</td>";
									echo "<td align='center'>".$me['modifytime']."</td>";
									echo "<td align='center'></td>";
									
									$sql="select * from tb_file_all  where id='".$me['parent_id']."'";  
									$res_parent=mysql_query($sql,$Con);
									$menu_parent=mysql_fetch_array($res_parent);
									if($menu['parent_id']=='0')//如果没有父目录就默认为根目录"/"
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
echo"<a href='updown.php'>返回首页</a><br>";
?>
</td>
</tr>
</table>
</body>             
</html> 
