<?php /*?> <?php
if(!$_COOKIE['admin']['user_id']){
	echo "δ��½";
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
   $stime=microtime(true); //��ȡ����ʼִ�е�ʱ��
require("../include/comment.php");
require("../include/user.class.php");

$user =&username::getInstance();
  $rootid =$_GET["id"];
/* //�������ݿ� 
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
  //������������             
  $GLOBALS["ID"]   =1;   //�������������˵���ID��             
  $layer=1;   //�������ٵ�ǰ�˵��ļ���    
           
  $sql="select   *   from   tb_file_all   where   id ='".$rootid."' and fileowner='".$username."' group by name";   
 // echo $sql;   
  $result=mysql_query($sql,$user->Con1);
  $menu=mysql_fetch_array($result); 
 /* echo "�����ڵ�Ŀ¼��".$menu[parent_name]."/".$menu[name];//��ʾ������Ŀ¼*/
 echo "�����ڵ�Ŀ¼��".$menu[name]."/";
  echo  "<br>";
	                
  $sql="select   *  ,count(*) from   tb_file_all   where   parent_id ='".$rootid."' and fileowner='".$username."' group by name";          
  $result=mysql_query($sql,$user->Con1);             
         
  //���һ���˵�������ʼ�˵�����ʾ             
  if(mysql_num_rows($result)>0)
  {
      ShowTreeMenu($user->Con1,$result,$layer,$username,$ID);
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
  function   ShowTreeMenu($Con,$result,$layer,$username)             
  {             
		  //ȡ����Ҫ��ʾ�Ĳ˵�����Ŀ��             
		  $numrows=mysql_num_rows($result);             
		  //��ʼ��ʾ�˵���ÿ���Ӳ˵�����һ���������ʾ             
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
			  //����ǰ�˵���Ŀ�����ݵ�������             
		         $menu=mysql_fetch_array($result);  
                 if($menu["count(*)"]==1)//˵��ֻ��һ���汾
				  {  
		            echo   "<tr>";             
					  //����ò˵���Ŀ��Ŀ¼�������JavaScript   onClick���             
					  if($menu['filetype']=='dir') //��Ŀ¼�Ĳ���          
					  {             
					  echo   "<td><img   src='folder.gif'   border='0'></td>";             
					 echo   "<td class='Menu' onClick=\"Querydir('".$menu[id]."');\">"; 
					 
					//  echo   "<td class='Menu' onClick=\"tt('abcde');\">";
					   $sql="select * from tb_file_location where id='".$menu[id]."'";
						 $res=mysql_query($sql,$Con);
						 $menu11=mysql_fetch_array($res);
						 echo "<".$menu11[serverip].">";
								  
						 echo   $menu[name];

						 echo " <br><a href='delconfirm.php?dir=$menu11[locationpath]&id=$menu[id]&name=$menu[name]&serverip=$menu11[serverip]'>[ɾ��]</a>";						 echo  "<a href='rename.php?name=$menu[name]&id=$menu[id]'>[����]</a>";      
					  	}             
					  else //���ļ��Ĳ���      
					  { 
					         echo   "<td><img	src='file.gif'   border='0'></td>";             
							  echo   "<td class='Menu'>";     
								 $sql="select * from tb_file_location where id='".$menu[id]."'";
								 $res=mysql_query($sql,$Con);
								 $menu11=mysql_fetch_array($res);
								 echo "<".$menu11[serverip].">";
								 echo   "<a href='downloadfile.php?dir=$menu11[locationpath]&id=$menu[id]&name=$menu[name]&serverip=$menu11[serverip]'>".$menu[name]."</a>"; 
								 echo " </br><a href='delfileconfirm.php?dir=$menu11[locationpath]&id=$menu[id]&name=$menu[name]&serverip=$menu11[serverip]'>[ɾ��]</a>";
								 echo  "<a href='rename.php?name=$menu[name]&id=$menu[id]'>[����]</a>";   
						 
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
				 else if($menu["count(*)"]>=2) //˵���ж���汾
				 {	   
				 //----------------------------------------------------------------
					 $sql="select * from tb_file_all where name='".$menu[name]."'";//��汾Ӧ����tb_file_all�в�
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
							 echo   "<a href='downloadfile.php?dir=$me11[locationpath]&id=$me[id]&name=$me[name]&serverip=$me11[serverip]'>"."�汾".$me[version]."</a>"; 
//------------------------------------------------------------------------------------------------------
/* $fp=fopen("zn1.txt","a");
  fwrite($fp,mysql_num_rows($result));
  fclose($fp);*/
//--------------------------------------------------------------------------------------------------------
						 echo " </br><a href='delfileconfirm.php?dir=$me11[locationpath]&id=$me[id]&name=$me[name]&serverip=$me11[serverip]'>[ɾ��]</a>";
							// echo  "<a href='rename.php?name=$me[name]&id=$me[id]'>[����]</a>";  
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
	<p><a href="up_downloadmanager.php?parent_id=<?php echo $rootid;?>"><strong>�ϴ��ļ�</strong></a></p>
	<form action="createnewdir.php?parent_id=<?php echo $rootid;?>" method="post">
	 <p><strong>�½�Ŀ¼</strong></p>
	 ����Ŀ¼����<input name="dirname" type="text">
	 <p><input name="create" type="submit" value="����"> 
	   <input name="reset" type="reset" value="���"></p>
	 </form>
	<?php
	echo"<a href='updown.php'>������ҳ</a><br>";
	echo"<a href='prequery_all.php?id=".$rootid."'>������һ��Ŀ¼</a>";
	?>
	 </td>
    </tr>
  </table>
  </body>             
  </html> 
