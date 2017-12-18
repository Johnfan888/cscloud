<?php
	
	function Curl($url, $header, $info){
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$info);
		$response=curl_exec($ch);
		curl_close($ch);
		return json_decode($response);
	}
	//��¼��֤
	function Auth($url,$header){
			$user="Admin";
			$password="zabbix";
			$logininfo = array(  //��¼����Ĳ���
		  	'jsonrpc' => '2.0',
		  	'method' => 'user.login',
		  	'params' => array(
		    'user' => $user,
		    'password' => $password,
		  ),
		  'id' => 1,
		);
		$data = json_encode($logininfo);
		$result = Curl($url,$header,$data);
		$token=$result->result; //�����ֵ֤
		return $token;
}
?>
