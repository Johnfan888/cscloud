<?php /*?> <?php
if(!$_COOKIE['admin']['user_id']){
	echo "未登陆";
	exit();
}
?><?php */?>
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
   $stime=microtime(true); //获取程序开始执行的时间
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();
  $rootid =$_GET["id"];
/* //连接数据库 
 $Con=mysql_connect("localhost","root","");             
         mysql_query("set names 'gbk'");   
		 mysql_select_db("work1");
	   */
  if($_COOKIE['admin']['username'])
{
     $username=$_COOKIE['admin']['username'];
	
}
else
{
   $username=$_GET['user'];
   echo "geruser=".$username;
  
			setcookie("admin[username]", $username);
   //echo  $_COOKIE['admin']['username'];  
}
  
  set_time_limit(0);
  //基本变量设置             
  $GLOBALS["ID"]   =1;   //用来跟踪下拉菜单的ID号             
  $layer=1;   //用来跟踪当前菜单的级数    
           
  $sql="select   *   from   tb_file_all   where   id ='".$rootid."' and fileowner='".$username."' group by name";   
 // echo $sql;   
  $result=mysql_query($sql,$user->Con1);
  $menu=mysql_fetch_array($result); 
 /* echo "你所在的目录：".$menu[parent_name]."/".$menu[name];//显示了两级目录*/
 echo "你所在的目录：".$menu[name]."/";
  echo  "<br>";
	                
  $sql="select   *  ,count(*) from   tb_file_all   where   parent_id ='".$rootid."' and fileowner='".$username."' group by name";          
  $result=mysql_query($sql,$user->Con1);             
         
  //如果一级菜单存在则开始菜单的显示             
  if(mysql_num_rows($result)>0)
  {
      ShowTreeMenu($user->Con1,$result,$layer,$username,$ID);
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
  function   ShowTreeMenu($Con,$result,$layer,$username)             
  {             
		  //取得需要显示的菜单的项目数             
		  $numrows=mysql_num_rows($result);             
		  //开始显示菜单，每个子菜单都用一个表格来表示             
		  echo   "<table   cellpadding='0'   cellspacing='0'   border='0'>";             
		  echo "<tr>
		          <td width='5%'>icon</td>
				  <td width='30%'>filename</td>
				  <td width='3%' align='center'>version</td>
				  <td width='10%' align='center'>size</td>
				  <td width='14%'>createtime</td>
				  <td width='14%'>visittime</td>
				  <td width='14%'>modifytime</td>
				 
				</tr>";			
		  for($rows=0;$rows<$numrows;$rows++)             
		  {             
			  //将当前菜单项目的内容导入数组             
		         $menu=mysql_fetch_array($result);  
                 if($menu["count(*)"]==1)//说明只有一个版本
				  {  
		            echo   "<tr>";             
					  //如果该菜单项目是目录，则添加JavaScript   onClick语句             
					  if($menu['filetype']=='dir') //对目录的操作          
					  {             
					  echo   "<td><img   src='folder.gif'   border='0'></td>";             
					 echo   "<td class='Menu' onClick=\"Querydir('".$menu[id]."');\">"; 
					 
					//  echo   "<td class='Menu' onClick=\"tt('abcde');\">";
					   $sql="select * from tb_file_location where id='".$menu[id]."'";
						 $res=mysql_query($sql,$Con);
						 $menu11=mysql_fetch_array($res);
						 echo "<".$menu11[serverip].">";
								  
						 echo   $menu[name];

						 echo " <br><a href='delconfirm.php?dir=$menu11[locationpath]&id=$menu[id]&name=$menu[name]&serverip=$menu11[serverip]'>[删除]</a>";						 echo  "<a href='rename.php?name=$menu[name]&id=$menu[id]'>[改名]</a>";      
					  	}             
					  else //对文件的操作      
					  { 
					         echo   "<td><img	src='file.gif'   border='0'></td>";             
							  echo   "<td class='Menu'>";     
								 $sql="select * from tb_file_location where id='".$menu[id]."'";
								 $res=mysql_query($sql,$Con);
								 $menu11=mysql_fetch_array($res);
								 echo "<".$menu11[serverip].">";
								 echo   "<a href='downloadfile.php?dir=$menu11[locationpath]&id=$menu[id]&name=$menu[name]&serverip=$menu11[serverip]'>".$menu[name]."</a>"; 
								 echo " </br><a href='delfileconfirm.php?dir=$menu11[locationpath]&id=$menu[id]&name=$menu[name]&serverip=$menu11[serverip]'>[删除]</a>";
								 echo  "<a href='rename.php?name=$menu[name]&id=$menu[id]'>[改名]</a>";   
						 
						 }             
					  
					  echo "</td>";
					  echo "<td align='center'>".$menu[version]."</td>";
					  echo "<td>".$menu[size]."</td>";
					  echo "<td>".$menu[createtime]."</td>";
					  echo "<td>".$menu[visittime]."</td>";
					  echo "<td>".$menu[modifytime]."</td>";
					  $sql="select * from tb_file_all  where id='".$menu[parent_id]."'";  
					  $res_parent=mysql_query($sql,$Con);
					  $menu_parent=mysql_fetch_array($res_parent);
				
					
                      echo "</strong></font></td>";                       
                      echo   "</tr>"; 
			      
		         }    
				 else if($menu["count(*)"]>=2) //说明有多个版本
				 {	   
				 //----------------------------------------------------------------
					 $sql="select * from tb_file_all where name='".$menu[name]."'";//多版本应该在tb_file_all中查
					 $rere=mysql_query($sql,$Con);
					  echo   "<tr><td><img	src='file.gif'   border='0'></td><td>".$menu[name]."</td></tr>";  
					  $numnum=mysql_num_rows($rere);
					 for($rows1=0;$rows1<$numnum;$rows1++) 
				     {
				         $me=mysql_fetch_array($rere);
						   echo   "<tr>";       
							 echo   "<td></td>";             
						     echo   "<td class='Menu'>";     
							 $sql="select * from tb_file_location where id='".$me[id]."'";
							 $res=mysql_query($sql,$Con);
							 $me11=mysql_fetch_array($res);
						 echo "<".$me11[serverip].">";
							 echo   "<a href='downloadfile.php?dir=$me11[locationpath]&id=$me[id]&name=$me[name]&serverip=$me11[serverip]'>"."版本".$me[version]."</a>"; 
//------------------------------------------------------------------------------------------------------
/* $fp=fopen("zn1.txt","a");
  fwrite($fp,mysql_num_rows($result));
  fclose($fp);*/
//--------------------------------------------------------------------------------------------------------
						 echo " </br><a href='delfileconfirm.php?dir=$me11[locationpath]&id=$me[id]&name=$me[name]&serverip=$me11[serverip]'>[删除]</a>";
							// echo  "<a href='rename.php?name=$me[name]&id=$me[id]'>[改名]</a>";  
							  echo "</td>";
							  echo "<td align='center'>".$me[version]."</td>";
							  echo "<td>".$me[size]."</td>";
							  echo "<td>".$me[createtime]."</td>";
							  echo "<td>".$me[visittime]."</td>";
							  echo "<td>".$me[modifytime]."</td>";
							  $sql="select * from tb_file_all  where id='".$me[parent_id]."'";  
							  $res_parent=mysql_query($sql,$Con);
							  $menu_parent=mysql_fetch_array($res_parent);
							 
							
							  echo "</strong></font></td>";                       
							  echo   "</tr>"; 
						   }
				 
				 }     
						 	 
		  }             
		  echo   "</table>";             
 }        
  
  ?>
  </td>
    <td width="30%" align="center">
	<p><a href="up_downloadmanager.php?parent_id=<?php echo $rootid;?>"><strong>上传文件</strong></a></p>
	<form action="createnewdir.php?parent_id=<?php echo $rootid;?>" method="post">
	 <p><strong>新建目录</strong></p>
	 输入目录名：<input name="dirname" type="text">
	 <p><input name="create" type="submit" value="创建"> 
	   <input name="reset" type="reset" value="清空"></p>
	 </form>
	<?php
	echo"<a href='updown.php'>返回首页</a><br>";
	echo"<a href='prequery_all.php?id=".$rootid."'>返回上一级目录</a>";
	?>
	 </td>
    </tr>
  </table>
  </body>             
  </html> 
