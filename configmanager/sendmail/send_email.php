<?php

include_once('class.phpmailer.php');

$mail    = new PHPMailer();

$body    = $mail->getFile('contents.html');
$subject="this is a test";
$body    = eregi_replace("[\]",'',$body);
$subject = eregi_replace("[\]",'',$subject);

$mail->From     = "162161518@qq.com";
$mail->FromName = "First Last";

$mail->Subject = "Fileserver transfered";

$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);

$mail->AddAddress("162161518@qq.com", "echo");

if(!$mail->Send()) {
  echo 'Failed to send mail';
} else {
  echo 'Mail sent';
}

?>
