<?php
header('Content-Type:text/html;charset=gb2312');
//ѡ����ҪǨ���ļ��ķ�������������ߵģ�
class TransferServer
{
	var $serverip;
	var $url;
	var $header;
	function TransferServer(){
		$ip=exec("/bin/sh  /srv/www/htdocs/configmanager/getlocalip.sh");
//		$array=explode("/",$ip);
//		$localip=$array[0];
		$localip=trim($ip,'"');

     	//exec("/bin/sh  /srv/www/htdocs/configmanager/getlocalip.sh",$array);
        //echo $array[0];
        //$localip=explode("/",$array[0]);
        //$localip=$localip[0];
        //$localip=trim($localip,"\'");
		//echo $localip;


		$this->serverip=$localip; 
		$this->url="http://".$this->serverip."/zabbix/api_jsonrpc.php";
		$this->header=array("Content-type: application/json-rpc");
	}
	function Curl($info){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $this->url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$this->header);
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $info);
    $response = curl_exec($ch); 
    curl_close($ch);
    return json_decode($response);	
	}

	
	function Login(){ //��¼��֤�ĺ���
		$user = 'Admin';
		$password = 'zabbix';
		$logininfo = array(  
	    'jsonrpc' => '2.0',
	    'method' => 'user.login',
	    'params' => array(
	    'user' => $user,
	    'password' => $password,
	  ),
	  'id' => 1,
	);
	
		$data = json_encode($logininfo);
		$result = self::Curl($data);
		$token=$result->result;
		return $token;
		
	}
		//�Ƿ����и���ֵ���ܸ�
		function LoadExceed(){
				$token=self::Login(); 
				$itemid=array(
								'jsonrpc'=>'2.0',
								'method'=>'item.get',
								'params'=>array(
								"output"=>"extend",
								"filter"=>array(
									"key_"=>"vfs.fs.size[/data,pused]",
					                "status"=>"0",
									),
								),
								"auth"=>$token,
								"id"=>3
							);
							$data=json_encode($itemid);
							$result=self::Curl($data);
							$result=$result->result;
							$count=count($result);
							for($i=0;$i<$count;$i++){  //��ȡ����hostid��itemid
								$itemid[$i]=$result[$i]->itemid;
								$hostid[$i]=$result[$i]->hostid;
							}
							for($j=0;$j<$count;$j++){		
									$fspuseds=array(
										'jsonrpc'=>'2.0',
										'method'=>'history.get',
										'params'=>array(
											'output'=>'extend',
											'history'=>'0',
											'itemids'=>$itemid[$j],
											"sortfield"=>"clock",
						        			"sortorder"=>"DESC",
						       			    "limit"=>'1'
										),
										'auth'=>$token,
										'id'=>'5',
									);
									$data=json_encode($fspuseds);
									$result=self::Curl($data);
									$result=$result->result;
									$fspusedvalue=$result[0]->value;
//                                    $aa=$result[0]->value;
                                 //����С
									if( $j==0 || $maxvalue > $fspusedvalue){
										$maxvalue=$fspusedvalue;
										$maxhostid=$hostid[$j];
									}
									else{
										$maxvalue=$maxvalue;
										$maxhostid=$maxhostid;
									}

									//-----------�����
									if( $j==0 || $maxmaxvalue < $fspusedvalue){
										$maxmaxvalue=$fspusedvalue;
//										$maxhostid=$hostid[$j];
									}
									else{
										$maxmaxvalue=$maxmaxvalue;
//										$maxhostid=$maxhostid;
									}
								}


								//ȡ�����ֵ!!!
								//-----------------$maxvalue��С���أ�������
								require('configure_class.php');
								$c=new Configuration;
								$c->configFile="/srv/www/htdocs/configmanager/config/config.txt";
								$c->_construct();
								$loading=$c->_get("MaxLoading_threshold");
								$loading=$loading*100;


								$load_threshold=$c->_get("Loading_threshold");
								$load_threshold=$load_threshold*100;
								if($load_threshold >= $maxmaxvalue){
								    $result='needless';
								}
								elseif($loading <= $maxvalue){
									$result='loadexceed';
								}
								else{
									$result='ok';
								}
								return $result;
			}
	function FindTransferServer($var,$ip){
			$token=self::Login();
			$itemid=array(
						'jsonrpc'=>'2.0',
						'method'=>'item.get',
						'params'=>array(
						"output"=>"extend",
						"filter"=>array(
							"key_"=>"vfs.fs.size[/data,pused]",
			                "status"=>"0",
							),
						),
						"auth"=>$token,
						"id"=>3
					);
					$data=json_encode($itemid);
					$result=self::Curl($data);
					$result=$result->result;
					$count=count($result);
					for($i=0;$i<$count;$i++){  //��ȡ����hostid��itemid
						$itemid[$i]=$result[$i]->itemid;
						$hostid[$i]=$result[$i]->hostid;
					}
					
			if($var == "max"){
			for($j=0;$j<$count;$j++){		
				$fspuseds=array(
					'jsonrpc'=>'2.0',
					'method'=>'history.get',
					'params'=>array(
						'output'=>'extend',
						'history'=>'0',
						'itemids'=>$itemid[$j],
						"sortfield"=>"clock",
	        			"sortorder"=>"DESC",
	       			    "limit"=>'1'
					),
					'auth'=>$token,
					'id'=>'4',
				);
				$data=json_encode($fspuseds);
				$result=self::Curl($data);
				$result=$result->result;
				$fspusedvalue=$result[0]->value;
				if( $j==0 ||$maxvalue < $fspusedvalue){
					$maxvalue=$fspusedvalue;
					$maxhostid=$hostid[$j];
				}
				else{
					$maxvalue=$maxvalue;
					$maxhostid=$maxhostid;
					
				}
			}	
		}	
		if($var == "min"){  //����� ����С����
			for($j=0;$j<$count;$j++){		
				$fspuseds=array(
					'jsonrpc'=>'2.0',
					'method'=>'history.get',
					'params'=>array(
						'output'=>'extend',
						'history'=>'0',
						'itemids'=>$itemid[$j],
						"sortfield"=>"clock",
	        			"sortorder"=>"DESC",
	       			    "limit"=>'1'
					),
					'auth'=>$token,
					'id'=>'5',
				);
				$data=json_encode($fspuseds);
				$result=self::Curl($data);
				$result=$result->result;
				$fspusedvalue=$result[0]->value;
				if( $j==0 || $maxvalue > $fspusedvalue){ 
					$maxvalue=$fspusedvalue;
					$maxhostid=$hostid[$j];
				}
				else{
					$maxvalue=$maxvalue;
					$maxhostid=$maxhostid;	
				}
			}	
		}
		//���ݷ�������id��ȡip��ַ
		$hostip=array(
			'jsonrpc'=>'2.0',
			'method'=>'hostinterface.get',
			'params'=>array(
				'output'=>'extend',
				//'hostids'=>$maxhostid
				'hostids'=>$maxhostid,

			),
			'auth'=>$token,
			'id'=>'6'
		);
		$data=json_encode($hostip);
		$result=self::Curl($data);
		$result=$result->result;
		//print_r($result);
		$hostip=$result[0]->ip;
			$maxvalue=$maxvalue/100;
			$result=array($hostip,$maxvalue,$maxhostid); 
			return $result; // ��õ������ֵ
		}
		function TargetServerTotalSize($hostid){ //ȡĿ����������ܴ洢
			$token=self::Login();
			$itemid=array(
						'jsonrpc'=>'2.0',
						'method'=>'item.get',
						'params'=>array(
						"output"=>"extend",
						"hostids"=>$hostid,
						"filter"=>array(
							"key_"=>"vfs.fs.size[/data,total]",
			                "status"=>"0",
							),
						),
						"auth"=>$token,
						"id"=>3
					);
					$data=json_encode($itemid);
					$result=self::Curl($data);
					$result=$result->result;
					$itemid=$result[0]->itemid; //��ȡ��itemid
					//return $itemid;
					//��ȡ��ֵ
					$TotalSize=array(
					'jsonrpc'=>'2.0',
					'method'=>'history.get',
					'params'=>array(
						'output'=>'extend',
						'history'=>'3',
						'itemids'=>$itemid,
						"sortfield"=>"clock",
	        			"sortorder"=>"DESC",
	       			    "limit"=>'1'
					),
					'auth'=>$token,
					'id'=>'4',
				);
				$data=json_encode($TotalSize);
				$result=self::Curl($data);
				$result=$result->result;
				$totalsize=$result[0]->value;	//��ȡ��ֵ
				return $totalsize;
		}
	 //Ŀ��������Ѿ�ʹ�õĸ���ֵ
	 function TargetServerUsedSize($hostid){
	 		$token=self::Login();
			$itemid=array(
						'jsonrpc'=>'2.0',
						'method'=>'item.get',
						'params'=>array(
						"output"=>"extend",
						"hostids"=>$hostid,
						"filter"=>array(
							"key_"=>"vfs.fs.size[/data,used]",
			                "status"=>"0",
							),
						),
						"auth"=>$token,
						"id"=>3
					);
					$data=json_encode($itemid);
					$result=self::Curl($data);
					$result=$result->result;
					$itemid=$result[0]->itemid; //��ȡ��itemid
					//��ȡ��ֵ
					$UsedSize=array(
					'jsonrpc'=>'2.0',
					'method'=>'history.get',
					'params'=>array(
						'output'=>'extend',
						'history'=>'3',
						'itemids'=>$itemid,
						"sortfield"=>"clock",
	        			"sortorder"=>"DESC",
	       			    "limit"=>'1'
					),
					'auth'=>$token,
					'id'=>'4',
				);
				$data=json_encode($UsedSize);
				$result=self::Curl($data);
				$result=$result->result;
				$UsedSize=$result[0]->value;	//��ȡ��ֵ
				return $UsedSize;
	 }
		
}
function selectsourceserver($loading)
{
	require_once("configure_class.php");
	$c = new Configuration();
	$c->configFile="/srv/www/htdocs/configmanager/config/config.txt";
	$c->_construct();
	$threshold=$c->_get("Loading_threshold");
   if($loading>$threshold)
   {
	return "need";
   }
}
//ѡ������͵ķ�����
function selecttargetserver($con,$haserveerip)
{	
	$sql="select * from ip_table where status='file' and ip_address != '$haserveerip'";
	$res=mysql_query($sql,$con);
	$nums=mysql_num_rows($res);
	for($i=0;$i<$nums;$i++)
	{
	$menu=mysql_fetch_array($res);
	$array[$menu["ip_address"]]=$menu["loading"];
	}
	$key = array_search(min($array), $array);
	return $key;
}


function selectuser($originip)  //�����û�
{
	require_once("./include/comment.php"); 
	require_once("./include/user.class.php"); 

	$tt =&username::getInstance();
	$sql="select count(*) from  T_UserZone where serverip='".$originip."'";
	$res=mysql_query($sql,$tt->Con1);
	$array=mysql_fetch_array($res);
	if($array["count(*)"]==1){
		return 0;
	}
	else{	
		$sql="select user_id from T_UserZone where server_ip='".$originip."' order by used_size asc limit 1"; //�鵽��Ϊ�û�id
		$result=mysql_query($sql,$tt->Con1);
		$menu=mysql_fetch_array($result);
		$fp=fopen("../manage/username.txt","w");
		fwrite($fp,$menu["user_id"]);
		fclose($fp);
		return $menu["user_id"];
	}


}




//
//$Boy = new TransferServer();
//$Boy -> TransferServer();

?>