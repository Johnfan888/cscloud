<?php
//不允许直接请求该页面
if(!defined('INC')) exit('Bad Request');

/**
 * 处理服务器信息的一些方法
 * author:张程
 */
 //获得所有服务器信息，返回的是一个二维数组
 function ServersInfo()
 {
	$sql = "select * from T_Server";
	$servers = $GLOBALS['db']->FetchAssoc($sql);
	return $servers;
 }

//获得一个指定IP的服务器信息
 function ServerInfo($ip)
 {
	 $sql = "select * from T_Server where server_ip='{$ip}'";
	 $server = $GLOBALS['db']->FetchAssocOne($sql);
	 return $server;
 }
 

class IsServerNormalWork{
	var $serverip;
	var $url;
	var $header;
	function IsServerNormalWork(){
		$path="./zsip.txt"; 
 		$content=file_get_contents($path);
 		$content=explode(":", $content);
 		$content=$content[1];
		$this->serverip=$content;
		$this->url='http://'.$this->serverip.'/zabbix1/api_jsonrpc.php';
		$this->header=array("Content-type: application/json-rpc");
		
	}
	function Curl($info){
	    $ch = curl_init();
	    curl_setopt($ch,CURLOPT_URL, $this->url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch,CURLOPT_HTTPHEADER,$this->header);
	    curl_setopt($ch,CURLOPT_POST, 1);
	    curl_setopt($ch,CURLOPT_POSTFIELDS, $info);
	    $response = curl_exec($ch); //返回的值
	    curl_close($ch);
	    return json_decode($response);
	}
	function Login(){
		$user='Admin';
		$password="zabbix";
		$logininfo = array(  //登录传入的参数
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
	$token=$result->result; //获得验证值
	return  $token;
	}
	function icmpping($ip){
		$token=self::Login();
		$hostinterface=array(
						'jsonrpc'=>'2.0',
					//	'method'=>'hostinterface.get',
					    'method'=>'host.get',
						'params'=>array(
						"output"=>"extend",
						"filter" => array(
							"host"=>$ip, //传入参数
							),
						),
						"auth"=>$token,
					    "id"=>2
					);
					$data=json_encode($hostinterface);
					$result =self::Curl($data);
					$result=$result->result; //获取对象中值得方法
		            $hostid=$result[0]-> hostid; //取得的hostid值
					//获得item的id值   获取固定主机的icmp的item的值
					$itemid=array(
						'jsonrpc'=>'2.0',
						'method'=>'item.get',
						'params'=>array(
						"output"=>"extend",
						"filter"=>array(
							"hostid"=>$hostid,
							"key_"=>"icmpping"
							),
						),
						"auth"=>$token,
						"id"=>3
					);
					$data=json_encode($itemid);
					$result=self::Curl($data);
					$result=$result->result;
					$itemids=$result[0]->itemid;
					//取得最近的ping的值
					$icmpping=array(
						'jsonrpc'=>'2.0',
						'method'=>'history.get',
						'params'=>array(
							"output"=>"extend",
							//"filter"=>array(
								"itemids"=>$itemids,
								"sortfield"=>"clock",
							    "sortorder"=> "DESC",
					       		"limit"=> 1
							//),
						),
						"auth"=>$token,
						"id"=>4
					);
					$data=json_encode($icmpping);
					$result=self::Curl($data);
					$result=$result->result;
					$icmppingvalue=$result[0]->value;
					return $icmppingvalue;
	}
}
	