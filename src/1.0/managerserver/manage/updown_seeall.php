<?php
/*if(!$_COOKIE['admin']['user_id']){
	echo "δ��½";
	exit();
}*/
?>
<html>             
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />    
<link rel='stylesheet' type='text/css' href='css/private.css'>           
<script   language="JavaScript"   src="TreeMenu.js"></script>   
<style type="text/css">
<!--
td#pointer{cursor: pointer;}
.STYLE2 {
color: #330099;
font-weight: bold;
}
-->
</style>
</head>             
<body> 
<table border=0 cellspacing=1 align=center class=Navi>
<tr>
<th>
<?php 
		require("../include/comment.php");
		require("../include/user.class.php");
		$user =&username::getInstance();
		
		echo "�û�".$_COOKIE['admin']['username'];
		$sql="select * from filesize where username='". $_COOKIE['admin']['username']."'";
		$res=mysql_query($sql,$user->Con1);
		$array=mysql_fetch_array($res);
	$totalsize=$array["totalsize"];
		$usedsize=$array["usedsize"];
		if($totalsize>=1000000000)
		{
			$totalsize=round($totalsize/1000000000,2);
			$unit="GB";
		}
		else if($totalsize>=1000000)
		{ 
			$totalsize=round($totalsize/1000000,2);
			$unit="MB";
		}else if($totalsize>=1000)
		{
			$totalsize=round($totalsize/1000,2);
			$unit="KB";
		}else if($totalsize>=0)
		{
			$unit="Byte";
		}
		
		if($usedsize>=1000000000)
		{
			$usedsize=round($usedsize/1000000000,2);
			$unit1="GB";
		}
		else if($usedsize>=1000000)
		{ 
			$usedsize=round($usedsize/1000000,2);
			$unit1="MB";
		}else if($usedsize>=1000)
		{
			$usedsize=round($usedsize/1000,2);
			$unit1="KB";
		}else
		{
			$unit1="Byte";
		}
		
		echo ",���Ŀռ�����Ϊ��".$totalsize.$unit;
		echo"��ǰ��ʹ�ã�".$usedsize.$unit1;
			
?></th>
</tr>
</table>
</br>
<table border="0" width='50%'cellspacing="0" cellpadding="0" align="center" class=Navi>
<tr><td>        
	<table border="0" cellspacing="1" cellpadding="0" align="center" class=Navi>
	<tr><td>	
	<p class="STYLE2"> ����ļ�</p>
	<table width='30%'border="1" cellspacing="0" cellpadding="0" align="center" class=Navi>
	<tr><td>
		<a href="up_downloadmanager.php?parent_id=0&user=<?php echo  $_COOKIE['admin']['username'];?>"><strong>�����ļ�</strong></a>
	</td></tr>
	<tr><td>
		<form action="createnewdir.php?parent_id=0" method="post">
		<p><strong>�½�Ŀ¼</strong></p>
		����Ŀ¼����<input name="dirname" type="text">
		<p><input name="create" type="submit" value="����"> 
		<input name="reset" type="reset" value="���">
		</form>
	</td></tr>
	</table>
</td></tr>
<tr><td>
	 <p class="STYLE2"> ��Ŀ¼�µ��ļ��б� <a href="updown.php"><strong>�鿴����������ļ��б�</strong></a></p>
	 <?php  
//   $stime=microtime(true); //��ȡ����ʼִ�е�ʱ��
if($_COOKIE['admin']['username'])
{
	$username=$_COOKIE['admin']['username'];
}
else
{
	$username=$_GET['owner'];
	echo "getuser=".$username;
	setcookie("admin[username]", $username);
}
// require("../include/config.php");

	set_time_limit(0);
	//������������             
	$GLOBALS["ID"]   =1;   //�������������˵���ID��             
	$layer=1;   //�������ٵ�ǰ�˵��ļ���             
	
	
	/*$sql="select * from tb_file_all ";        
	$re1=mysql_query($sql,$user->Con1);
	$num1=mysql_num_rows($re1);
	echo "tb_file_all�����ļ�����" .$num1;*/
	
	$sql="select * from tb_file_all where parent_id=0 and user_id=(select user_id from members where username='".$username."')";        
	$re1=mysql_query($sql,$user->Con1);
	$num=mysql_num_rows($re1);
	echo "��Ŀ¼�¹����ļ�����".$num;
	
		//����ÿҳ��ʾ���ļ���	
	$pagesize=10; 
	//����ҳ��
	$pages=ceil($num/$pagesize);
	//����ҳ��
	if (isset($_GET['page']))
	{
	  $page=$_GET['page'];
	}
	else
	{ 
	  $page=1;
	} 
	$offset=$pagesize*($page-1);
	
	
	//��ȡһ���˵�             
	$sql="select   * ,count(*)  from   tb_file_all  where parent_id=0 and user_id=(select user_id from members where username='".$username."') group by parent_id,name order by modifytime desc limit $offset,$pagesize";       
	$result=mysql_query($sql,$user->Con1);           
	//��ȡ�û�user_id
	$sql="select user_id from members where username='".$username."'";
	$res=mysql_query($sql,$user->Con1);
	$array=mysql_fetch_array($res);
	$user_id=$array["user_id"];           

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
	}
	//���һ���˵�������ʼ�˵�����ʾ             
	if(mysql_num_rows($result)>0)  
	        
	ShowTreeMenu($user->Con1,$result,$layer,$user_id,$version_flag,$getname,$menu_flag,$ID);           		
            
  //=============================================             
  //��ʾ���Ͳ˵�����   ShowTreeMenu($con,$result,$layer)             
  //$con�����ݿ�����             
  //$result����Ҫ��ʾ�Ĳ˵���¼��             
  //layer����Ҫ��ʾ�Ĳ˵��ļ���             
  //=============================================             
  function    ShowTreeMenu($Con,$result,$layer,$user_id,$version_flag,$getname,$menu_flag)             
  {             
		  //ȡ����Ҫ��ʾ�Ĳ˵�����Ŀ��             
	 //ȡ����Ҫ��ʾ�Ĳ˵�����Ŀ��             
		  $numrows=mysql_num_rows($result);             
		
		  //��ʼ��ʾ�˵���ÿ���Ӳ˵�����һ���������ʾ             
		  echo   "<table   cellpadding='0'   cellspacing='0'   border='0'>";             
		  echo "<tr>
		          <td width='5%'  align='center'></td>
				  <td width='30%'>filename</td>
				  <td width='10%' align='center'>size(kb)</td>
				  <td width='14%'  align='center'>option_time</td>
				  <td width='3%' align='center'>version_number</td>
				  <td width='10%' align='center'>parentdir</td>
				</tr>";			
		  for($rows=0;$rows<$numrows;$rows++)             
		  {             
		  //����ǰ�˵���Ŀ�����ݵ�������             
		  $menu=mysql_fetch_array($result);             
				if($menu["count(*)"]==1)//˵��ֻ��һ���汾
				{  
						echo   "<tr>";             
					  //����ò˵���Ŀ��Ŀ¼�������JavaScript   onClick���             
						if($menu['filetype']=='1') //��Ŀ¼�Ĳ���          
						{             
							echo   "<td><img   src='folder.gif'   border='0'></td>";             
							echo   "<td onClick=\"Querydir('".$menu["id"]."');\">"; 
							
							//  echo   "<td class='Menu' onClick=\"tt('abcde');\">";
							$sql="select * from tb_file_location where id='".$menu['id']."'";
							$res=mysql_query($sql,$Con);
							$menu11=mysql_fetch_array($res);
							
							echo "<".$menu11['serverip'].">";
							
							echo   $menu['name'];
							echo " <br><a href='query.php?id=$menu[id]'>[��]</a>";
							echo " <a href='delconfirm.php?dir=".$menu11['locationpath']."&id=".$menu['id']."&name=".$menu['name']."&serverip=".$menu11['serverip']."'>[ɾ��]</a>";						 echo  "<a href='rename.php?name=".$menu['name']."&id=".$menu['id']."'>[����]</a>";    
							echo "</td>";
							echo "<td></td>"; 
						
						}             
						else //���ļ��Ĳ���      
						{ 
							echo   "<td><img	src='file.gif'   border='0'></td>";             
							echo   "<td>";     
							$sql="select * from tb_file_location where id='".$menu['id']."'";
							$res=mysql_query($sql,$Con);
							$menu11=mysql_fetch_array($res);
							echo "<".$menu11['serverip'].">";
							echo   "<a href='downloadfile.php?dir=".$menu11['locationpath']."&id=".$menu['id']."&name=".$menu['name']."&serverip=".$menu11['serverip']."&replicaip=".$menu11['replicaip']."&replicalocation=".$menu11['replicalocation']."'>".$menu['name']."</a>"; 
							echo " </br><a href='delfileconfirm.php?dir=".$menu11['locationpath']."&id=".$menu['id']."&name=".$menu['name']."&serverip=".$menu11['serverip']."'>[ɾ��]</a>";
							echo  "<a href='rename.php?name=".$menu['name']."&id=".$menu['id']."'>[����]</a>";   
							echo "</td>";
							
							echo "<td  align='center'>".round($menu['size']/1000000,2)."</td>";
							
						} 
						if($menu['modifytime']>=$menu['visittime'])
						{
							echo "<td  align='center'>".$menu['modifytime']."</td>";
						}
						else
						{
							echo "<td  align='center'>".$menu['visittime']."</td>"; 
						}        
						echo "<td align='center'>".$menu['version']."</td>";				 
						
						$sql="select * from tb_file_all  where id='".$menu['parent_id']."'";  
						$res_parent=mysql_query($sql,$Con);
						$menu_parent=mysql_fetch_array($res_parent);
						if($menu['parent_id']=='0')//���û�и�Ŀ¼��Ĭ��Ϊ��Ŀ¼"/"
						{
							echo "<td align='center'>";
							$parent_name="/";
						}
						else{
							echo   "<td class='Menu' onClick=\"Querydir('".$menu["parent_id"]."');\"><font color='#0033FF'><strong>"; 
							$parent_name=$menu_parent[name];
						}
					  
						echo $parent_name;
						echo "</strong></font></td>";                       
						echo   "</tr>"; 
			      
		         }    
				 else  //˵���ж���汾
				 {	   
				 //----------------------------------------------------------------
						$sql="select * from tb_file_all where name='".$menu['name']."' and user_id='".$user_id."' and parent_id='".$menu['parent_id']."'";//��汾Ӧ����tb_file_all�в�
					 $rere=mysql_query($sql,$Con);
					if($menu_flag==0)
					{//˵���ǵ�һ�η��ʸ�ҳ
						 echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='updown_seeall.php?version_flag=1&name=".$menu['name']."'>��ϸ��Ϣ</a></td>";  
					}
					else
					{//˵����ͨ�������ϸ��Ϣ�����ʵĸ�ҳ
						 if(($version_flag=='0')&&($menu['name']==$getname))
						 {
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='updown_seeall.php?version_flag=1&name=".$menu['name']."'>��ϸ��Ϣ</a></td>";  
						 }
						 else if(($version_flag=='1')&&($menu['name']==$getname))
						 {
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='updown_seeall.php?version_flag=0&name=".$menu['name']."'>��ϸ��Ϣ</a></td>";  
						 }else{
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='updown_seeall.php?version_flag=1&name=".$menu['name']."'>��ϸ��Ϣ</a></td>";  
						}
					}
					echo"<td></td>";//size
					echo"<td></td>";//modifytime
					echo"<td align='center'>".$menu["count(*)"]."</td><td></td></tr>";//version
					if(($version_flag=='1')&&($menu['name']==$getname))//��ʾ��Ҫչ����汾�ļ�����ϸ��Ϣ
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
									
									if($menu['modifytime']>=$menu['visittime'])
									{
										echo "<td  align='center'>".$menu['modifytime']."</td>";
									}
									else
									{
										echo "<td  align='center'>".$menu['visittime']."</td>"; 
									}        
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
</td></tr>
<tr>
<td>
<?php
echo "<div align='center'>����".$pages."ҳ(".$page."/".$pages.")";
for ($i=1;$i<$page;$i++)
{
	echo "<a href='updown_seeall.php?page=".$i."'>[".$i ."]</a> ";
}
echo "[".$page."]";
for ($i=$page+1;$i<=$pages;$i++)
{echo "<a href='updown_seeall.php?page=".$i."'>[".$i ."]</a> ";}
echo "</div>";
?></td>
</tr>
<tr>
<td align=center>Copyright  &copy; 2011 <b></b>, All Rights Reserved .</td>
</tr>
<tr>
<td align=center>E-mail: admin@istl.chd.edu.cn </td>
</tr>
</table>
</body></html>
 
 