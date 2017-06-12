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
		
		echo "用户".$_COOKIE['admin']['username'];
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
		
		echo ",您的空间总量为：".$totalsize.$unit;
		echo"当前已使用：".$usedsize.$unit1;
			
	?></th>
  </tr>
</table>            
<table width="90%" border="1" cellspacing="0" cellpadding="0" align="center" class=Navi>
<tr><td>        
	<table border="0" cellspacing="1" cellpadding="0" align="center" class=Navi>
	<tr><td>	
	<p class="STYLE2"> 添加文件</p>
	<table width='30%'border="0" cellspacing="0" cellpadding="0" align="center" class=Navi>
	<tr><td>
		<a href="up_downloadmanager.php?parent_id=<?php echo $_GET["id"];?>&user=<?php echo $_COOKIE['admin']['username'];?>"><strong>备份文件</strong></a>
	</td></tr>
	<tr><td>
		<form action="createnewdir.php?parent_id=<?php echo $_GET["id"];?>" method="post">
		<p><strong>新建目录</strong></p>
		输入目录名：<input name="dirname" type="text">
		<p><input name="create" type="submit" value="创建"> 
		<input name="reset" type="reset" value="清空"></p>
		</form>
	</td></tr>
	</table>
</td></tr>
<tr>
<td width="30%">
<?php  
if($_COOKIE['admin']['username'])
{
	$username=$_COOKIE['admin']['username'];

}
else
{
	$username=$_GET['user'];
	echo "geruser=".$username;
	setcookie("admin[username]", $username);
}


  set_time_limit(0);
  //基本变量设置             
  $GLOBALS["ID"]   =1;   //用来跟踪下拉菜单的ID号             
  $layer=1;   //用来跟踪当前菜单的级数    

//获取目录信息
	$rootid =$_GET["id"];
	//echo "parent_id=".$rootid."<br>";
	$dirpath="/";
	$parentid=$rootid;
	while($parentid!=0)
	{
		$SQL="select parent_id,name from tb_file_all where id='".$parentid."'";
		$RES=mysql_query($SQL,$user->Con1);
		$MENU=mysql_fetch_array($RES);
		$parentid=$MENU["parent_id"];
		$dirpath="/".$MENU["name"].$dirpath;
	}
	echo "你所在的目录：".$dirpath."&nbsp;&nbsp;&nbsp;&nbsp;"; 
	echo"<a href='updown.php'><strong>返回首页</strong></a>"."&nbsp;&nbsp;&nbsp;&nbsp;";
	$sql="select parent_id from tb_file_all where id='".$rootid."'";
	$res=mysql_query($sql,$user->Con1);
	$array=mysql_fetch_array($res);
	if($rootid!=0)
	{		
		echo"<a href='query.php?id=".$array["parent_id"]."'><strong>返回上一级目录</strong></a>";
	}
	
 //获取用户的user_id 
	$sql="select user_id from members where username='".$username."'";
	$res=mysql_query($sql,$user->Con1);
	$array=mysql_fetch_array($res);
	$user_id=$array["user_id"];  
	
	$sql="select  * from   tb_file_all   where   parent_id ='".$rootid."' and user_id=(select user_id from members where username='".$_COOKIE['admin']['username']."')";  
//	echo $sql;
	$res=mysql_query($sql,$user->Con1);
	$num=mysql_num_rows($res);
	//设置每页显示的文件数	
	$pagesize=10; 
	//设置页数
	$pages=ceil($num/$pagesize);
	//设置页数
	if (isset($_GET['page']))
	{
	  $page=$_GET['page'];
	}
	else
	{ 
	  $page=1;
	} 
	$offset=$pagesize*($page-1);
	
	
	$sql="select   *  ,count(*)  from   tb_file_all   where   parent_id ='".$rootid."' and user_id=(select user_id from members where username='".$_COOKIE['admin']['username']."')  group by name limit $offset,$pagesize";  

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
	}      
	if(mysql_num_rows($result)>0)
	{
	  ShowTreeMenu($user->Con1,$result,$layer,$user_id,$version_flag,$getname,$menu_flag,$rootid,$ID);
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
	function   ShowTreeMenu($Con,$result,$layer,$user_id,$version_flag,$getname,$menu_flag,$rootid)             
	{             
		  //取得需要显示的菜单的项目数             
		$numrows=mysql_num_rows($result);             
		//开始显示菜单，每个子菜单都用一个表格来表示             
		echo   "<table   cellpadding='0'   cellspacing='0'   border='0'>";             
		echo "<tr>
			<td width='5%'></td>
			<td width='30%'>filename</td>
			<td width='10%' align='center'>size(MB)</td> 
			<td width='14%' align='center'>option_time</td>
			<td width='3%' align='center'>version_number</td> 
			</tr>";			
			for($rows=0;$rows<$numrows;$rows++)             
			{             
			  //将当前菜单项目的内容导入数组             
		         $menu=mysql_fetch_array($result);  
				if($menu["count(*)"]==1)//说明只有一个版本
				{  
						echo   "<tr>";             
					  //如果该菜单项目是目录，则添加JavaScript   onClick语句             
						if($menu['filetype']=='1') //对目录的操作          
						{             
							echo   "<td><img   src='folder.gif'   border='0'></td>";             
							echo   "<td onClick=\"Querydir('".$menu["id"]."');\">"; 
							
							$sql="select * from tb_file_location where id='".$menu["id"]."' and user_id='".$user_id."'";
							$res=mysql_query($sql,$Con);
							$menu11=mysql_fetch_array($res);
							echo "<".$menu11["serverip"].">";
							echo   $menu["name"];
							echo " <br><a href='query.php?id=$menu[id]'>[打开]</a>";
							echo " <a href='delconfirm.php?dir=$menu11[locationpath]&id=$menu[id]&name=$menu[name]&serverip=$menu11[serverip]'>[删除]</a>";						
							echo  "<a href='rename.php?name=$menu[name]&id=$menu[id]'>[改名]</a>";      					
							echo "</td>";
							echo "<td></td>";
						}             
						else //对文件的操作      
						{ 
							echo   "<td><img	src='file.gif'   border='0'></td>";             
							echo   "<td class='Menu'>";     
							$sql="select * from tb_file_location where id='".$menu["id"]."'";
							$res=mysql_query($sql,$Con);
							$menu11=mysql_fetch_array($res);
							echo "<".$menu11["serverip"].">";
							echo   "<a href='downloadfile.php?dir=".$menu11['locationpath']."&id=".$menu['id']."&name=".$menu['name']."&serverip=".$menu11['serverip']."'>".$menu['name']."</a>"; 
							echo " </br><a href='delfileconfirm.php?dir=".$menu11['locationpath']."&id=".$menu['id']."&name=".$menu['name']."&serverip=".$menu11['serverip']."'>[删除]</a>";
							echo  "<a href='rename.php?name=".$menu['name']."&id=".$menu['id']."'>[改名]</a>";   
							echo "</td>";
							echo "<td  align='center'>".round($menu["size"]/1000000,2)."</td>";
						}  
						if($menu["modifytime"]>$menu["visittime"])
						{           
					  		echo "<td  align='center'>".$menu["modifytime"]."</td>";
						}
						else
						{
							echo "<td  align='center'>".$menu["visittime"]."</td>";
						}
						echo "<td align='center'>".$menu["version"]."</td>";
						echo "</tr>" ;
			      
				}    
				else if($menu["count(*)"]>=2) //说明有多个版本
				{	   
				 //----------------------------------------------------------------
					$sql="select * from tb_file_all where name='".$menu["name"]."' and parent_id='".$rootid."' and user_id='".$user_id."'";//多版本应该在tb_file_all中查
					$rere=mysql_query($sql,$Con);
					
					if($menu_flag==0)
					{//说明是第一次访问该页
						 echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='query.php?version_flag=1&name=".$menu['name']."&id=".$rootid."'>详细信息</a></td>";  
					}
					else
					{//说明是通过点击详细信息而访问的该页
						 if(($version_flag=='0')&&($menu['name']==$getname))
						 {
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='query.php?version_flag=1&name=".$menu['name']."&id=".$rootid."'>详细信息</a></td>";  
						 }
						 else if(($version_flag=='1')&&($menu['name']==$getname))
						 {
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='query.php?version_flag=0&name=".$menu['name']."&id=".$rootid."'>详细信息</a></td>";  
						 }else{
						  echo   "<tr><td align='center'><img	src='file.gif'   border='0'></td>
						  <td>".$menu['name']."<a href='query.php?version_flag=1&name=".$menu['name']."&id=".$rootid."'>详细信息</a></td>";  
						}
					}
					echo"<td></td>";//size
					echo"<td></td>";//modifytime
					echo"<td align='center'>".$menu["count(*)"]."</td><td></td></tr>";//version
					if(($version_flag=='1')&&($menu['name']==$getname))//表示需要展开多版本文件的详细信息
					{ 
						$numnum=mysql_num_rows($rere);
						for($rows1=0;$rows1<$numnum;$rows1++) 
						{
							$me=mysql_fetch_array($rere);
							echo   "<tr>";       
							echo   "<td></td>";             
							echo   "<td class='Menu'>";     
							$sql="select * from tb_file_location where id='".$me["id"]."'";
							$res=mysql_query($sql,$Con);
							$me11=mysql_fetch_array($res);
							echo "<".$me11["serverip"].">";
							echo   "<a href='downloadfile.php?dir=".$me11['locationpath']."&id=".$me['id']."&name=".$me['name']."&serverip=".$me11['serverip']."'>"."版本".$me[version]."</a>"; 
							
							//--------------------------------------------------------------------------------------------------------
							echo " </br><a href='delfileconfirm.php?dir=".$me11['locationpath']."&id=".$me['id']."&name=".$me['name']."&serverip=".$me11['serverip']."'>[删除]</a>";
							// echo  "<a href='rename.php?name=$me[name]&id=$me[id]'>[改名]</a>";  
							echo "</td>";
							echo "<td  align='center'>".round($menu["size"]/1000000,2)."</td>";
							if($me["modifytime"]>$me["visittime"])
							{
								echo "<td  align='center'>".$me["modifytime"]."</td>";
							}
							else
							{
								echo "<td  align='center'>".$me["visittime"]."</td>";
							}
							echo "<td align='center'></td>";
							echo "</tr>";
						}
					}
				 }     
						 	 
			}             
		echo   "</table>";             
 } 
	
  ?>
</td></tr>
<tr><td>
<?php
echo "<div align='center'>共有".$pages."页(".$page."/".$pages.")";
for ($i=1;$i<$page;$i++)
{
	echo "<a href='query.php?id=".$rootid."&page=".$i."'>[".$i ."]</a> ";
}
echo "[".$page."]";
for ($i=$page+1;$i<=$pages;$i++)
{echo "<a href='query.php?id=".$rootid."&page=".$i."'>[".$i ."]</a> ";}
echo "</div>";
?></td></tr>

<tr>
<td align=center>Copyright  &copy; 2011 <b></b>, All Rights Reserved .</td>
</tr>
<tr>
<td align=center>E-mail: admin@istl.chd.edu.cn </td>
</tr>
</table>
</body>             
</html> 
