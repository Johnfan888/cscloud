<?php
/**
 * 负载均衡分配策略
 * author:张程
*/

//不允许直接请求该页面
if(!defined('INC')) exit('Bad Request');

//载入服务器信息处理助手
require(INC_PATH . '/server.helper.inc.php');
function SelectFileServer()
{
	
	 
	//获取存储文件服务器的ip数组
	$servers = ServersInfo();
	$nums = $GLOBALS['db']->NumRowsWithoutSql(); //总共服务器数
	$ips = array();
	$filepaths = array();
	
	if($servers)
	{
			  $fh=fopen('/srv/www/htdocs/conf/server_balance.txt','r');//读文件方式打开文件
	          for($i=0;$i<10;$i++){ //设置等待10s
		          if(flock($fh, LOCK_EX|LOCK_NB)){
						$a=fread($fh, filesize('/srv/www/htdocs/conf/server_balance.txt')); //加锁读取值
						$fhw=fopen('/srv/www/htdocs/conf/server_balance.txt','w+'); //写模式打开文件
		          		$b=$a+1;//读取完成后就加1
			 			if($b>1000) 
						{
							$b=0;
						}
						fwrite($fhw, $b);
						flock($fh, LOCK_UN);
						fclose($fhw);
						fclose($fh); //关闭文件后就解锁
						break;
		          }
				else{
					sleep(1);
					continue;
				}	
			 }
			 
		    if($i == 10){ //10s后没有解锁文件
		    	$fh1=fopen('/srv/www/htdocs/conf/server_balance.txt','r'); //打开文件
				$a=fread($fh1, filesize('/srv/www/htdocs/conf/server_balance.txt')); //直接读文件内容不判断
				fclose($fh1);
				$cmd="rm -rf /srv/www/htdocs/conf/server_balance.txt"; //删除文件
				system($cmd,$result);
				$fh2=fopen('/srv/www/htdocs/conf/server_balance.txt','w+');//创建文件,解锁
				$b=$a+1; 
		   		if($b>1000) 
				{
					$b=0;
				}
				fwrite($fh2,$b);//文件写入内容
				fclose($fh2);//关闭文件
		    }
		
		foreach($servers as $server)  //遍历数组
		{
			$ips[] = $server['server_ip']; //获取到的服务器ip的信息
			$paths[] = $server['file_path'];
		}
		$ping = new IsServerNormalWork();
		for($k=0;$k<$nums;$k++){
			$j=$a % $nums; //获取服务器
			$ip = $ips[$j];
			$path = $paths[$j];
			$icmppingvalue = $ping->icmpping($ip);
			if($icmppingvalue == '1'){ //判读是否正常工作
				$ip = $ips[$j];
				$path = $paths[$j];
					for($m=0;$m<$nums;$m++){ //获取副本服务器时也要判断是否正常工作
						if(($j+1) == $nums) //获取副本服务器
					    {
						   $ha_ip = $ips[0];
						   $ha_path = $paths[0];
					    }
					    else
					    {
							$ha_ip = $ips[$j+1];
						    $ha_path = $paths[$j+1];
					    }
					    $icmppingvalue1 = $ping->icmpping($ha_ip);
					    if($icmppingvalue1 == '1')
					    {	
					    	$ha_ip=$ha_ip;
					    	$ha_path=$ha_path;
					    	break;
					    }
					    else
					    {
					    	$j=$j+1; 
					    	if($j>$nums)
					    	{
					    		$j=$j%$num;
					    	}
					    	continue;
					    }
					}
				break;
			}
			else
			{
				  $fh=fopen('/srv/www/htdocs/conf/server_balance.txt','r');//读文件方式打开文件
				  for($j=0;$j<10;$j++)
				  { //设置等待10s
			          if(flock($fh, LOCK_EX|LOCK_NB))
			          {
							$a=fread($fh, filesize('/srv/www/htdocs/conf/server_balance.txt')); //加锁读取值
							$fhw=fopen('/srv/www/htdocs/conf/server_balance.txt','w+'); //写模式打开文件
			          		$b=$a+1;//读取完成后就加1
				 			if($b>1000) 
							{
								$b=0;
							}
							fwrite($fhw, $b);
							flock($fh, LOCK_UN);
							fclose($fhw);
							fclose($fh); //关闭文件后就解锁
							break;
			          }
					else
					{
						sleep(1);
					}	
				 }
			    if($j==10)
			    { //10s后没有解锁文件
			    	$fh1=fopen('/srv/www/htdocs/conf/server_balance.txt','r'); //打开文件
					$a=fread($fh1, filesize('/srv/www/htdocs/conf/server_balance.txt')); //直接读文件内容不判断
					fclose($fh1);
					$cmd="rm -rf /srv/www/htdocs/conf/server_balance.txt"; //删除文件
					system($cmd,$result);
					$fh2=fopen('/srv/www/htdocs/conf/server_balance.txt','w+');//创建文件,解锁
					$b=$a+1; 
			   		if($b>1000) 
					{
						$b=0;
					}
					fwrite($fh2,$b);//文件写入内容
					fclose($fh2);//关闭文件
			    }
				 continue;
			}
		}
		$result=array();
		$result['ip'] = $ip;
		$result['path'] = $path;
		$result['ha_ip'] = $ha_ip;
		$result['ha_path'] = $ha_path;
		return $result;
	}
	else
		return null;
}
?>