<?php

//生成Email脚本
class RandEmail{
	public $length;
	public $DomainName;
	function _construct($length,$domainname){
		$this->length=$length;
		$this->DomainName=$domainname;
	}
	function getRandChar(){
		$str=null;
		$strPol="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max=strlen($strPol)-1;

		for($i=0;$i<$this->length;$i++){
			$str.=$strPol[rand(0,$max)];
		}
		$str=$str.'@'.$this->DomainName;
		return $str;
		//return $max;
	}
}

$DomainName=array('qq.com','163.com','126.com','sina.com.cn','yahoo.com','msn.com');
$DomainNameNum=count($DomainName)-1;
$num=50; //生成Email的个数
$fh=fopen("./email.txt", "w+");
for($i=0;$i<$num;$i++){
	$RandDomainName=$DomainName[rand(0,$DomainNameNum)];
	$RandNum=rand(3,7);
	$RandEmail=new RandEmail;
	$RandEmail->_construct($RandNum,$RandDomainName); 	
	$email=$RandEmail->getRandChar();
	fwrite($fh, $email."\n");
}
fclose($fh);

?>