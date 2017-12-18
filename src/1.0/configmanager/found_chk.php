<?php
	header('Content-Type:text/html;charset=gb2312');
	include_once 'conn/conn.php';
	$reback = '0';
	$name = $_GET['foundname'];
	$question = $_GET['question'];
	$answer = $_GET['answer'];
	$sql = "select email from tb_member where name = '".$name."' and question = '".$question."' and answer = '".$answer."'";
	$email = $conne->getFields($sql,0);
	
	//if($email != ''){
		$rnd = rand(1000,time());
		$sql = "update tb_member set password = '".md5($rnd)."' where name = '".$name."' and question = '".$question."' and answer = '".$answer."'";
		$tmpnum = $conne->uidRst($sql);
		if($tmpnum >= 1){
			//·¢ËÍÃÜÂëÓÊ¼þ
			$subject="ÕÒ»ØÃÜÂë";
			$mailbody='ÃÜÂëÕÒ»Ø³É¹¦¡£ÄúÕÊºÅµÄÐÂÃÜÂëÊÇ'.$rnd;
			/*$envelope["from"]="echo0104@126.com";
			$part1["type"] = TYPEMULTIPART;
			$part1["subtype"] = "mixed";
			$part2["type"] = TYPETEXT;
			$part2["subtype"] = "plain";
			$part2["encoding"] = ENCBINARY;
			$part2["contents.data"] = "$mailbody\n\n\n\t";
			$body[1] = $part1;
			$body[2] = $part2;
			$message=imap_mail_compose($envelope, $body);
			$to=$email;
			list($msgheader,$msgbody)=split("\r\n\r\n",$message,2);
			$sendes=imap_mail($to,$subject,$msgbody,$msgheader);
			if(false == $sendes){
				$reback = '-1';
			}else{
				$reback = '1';
			}*/
			$reback = $mailbody;
		}else{
			$reback = '2';
		}
	/*}else{
		$reback = $sql;
	}*/
	//echo $reback;
	echo $reback;
?>
