<?php
final class mail{
    private $from=null,$pass=null,$to=null;
    private $smtp=null,$port=null;
    private $socket=null;
    private $data=null;   
    public function __construct(){//构造函数   
				$c = new Configuration();
				$c->configFile="/srv/www/htdocs/configmanager/config/mailinfo_config.txt";
				$c->_construct();
				$smtp=$c->_get("Server");
				$port=$c->_get("Port");
				$from=$c->_get("From");
				$passwd=$c->_get("Passwd");
				$to=$c->_get("To");
				$array=array($smtp,$port,$from,$passwd,$to);
		        $this->smtp=$array[0];
		        $this->port=$array[1];
		        $this->from=$array[2];
		        $this->pass=$array[3];
		        $this->to=$array[4];
    }
     
    public function send($header=null,$body=null){
    	$fh=fopen("/srv/www/htdocs/configmanager/sendmail/log_mail.txt",'w+');
    	$date=date('Y-m-d h:m:s');
    	fwrite($fh,$date);
        $this->socket=socket_create(AF_INET,SOCK_STREAM,getprotobyname('tcp')); //创建与函数连接的句柄
        if(!$this->socket){
            $this->log('创建socket失败',true); 
        }
         else{
         	$this->log('创建socket成功');
        
         }
     	if(socket_connect($this->socket,$this->smtp,$this->port)){ //使用句柄与相应的邮件服务器建立连接
        
            $this->log('服务器连接应答:'.socket_strerror(socket_last_error()));
        }
        else{
            $this->log('socket连接失败',true);
        }
         
        $this->data="EHLO HELO\r\n"; //发送标识命令，向服务器标识自己
        $this->do_send();
         
        $this->data="AUTH LOGIN\r\n"; //发送命令AUTH LOGIN，返回334，要求认证
        $data1=$this->do_send();
        fwrite($fh,'客户端发送:'.$this->data);
        fwrite($fh,'服务器应答：'.$data1);
         
        $this->data=base64_encode($this->from)."\r\n"; //发送base64编码之后的用户名
        $this->do_send();
        fwrite($fh,'客户端发送:'.$this->data);
        fwrite($fh,'服务器应答：'.$data1);
          
        $this->data=base64_encode($this->pass)."\r\n"; //发送base64编码之后的密码，服务器返回235
        $this->do_send();
        fwrite($fh,'客户端发送:'.$this->data);
        fwrite($fh,'服务器应答：'.$data1);
           
        $this->data="MAIL FROM:<".$this->from.">\r\n";// 登录成功之后，发送命令MAIL FROM:< >，服务器返回250，邮件发送者
        $this->do_send();
        fwrite($fh,'客户端发送:'.$this->data);
        fwrite($fh,'服务器应答：'.$data1);
          
        $this->data="RCPT TO:<".$this->to.">\r\n"; //发送命令RCPT TO:< >，服务器返回250，邮件接收者
        $this->do_send();
        fwrite($fh,'客户端发送:'.$this->data);
        fwrite($fh,'服务器应答：'.$data1);
         
        $this->data="DATA\r\n"; //发送命令DATA，服务器返回354，之后可以发送邮件内容了
        $this->do_send();
        fwrite($fh,'客户端发送:'.$this->data);
        fwrite($fh,'服务器应答：'.$data1);
         
        $this->data="From:匿名<".$this->from.">\r\n"; 
        $this->data.="Subject:".$header."\r\n\r\n"; //在代码里面，没发送一条命令，用\r\n结束输入。 
        $this->data.=$body."\r\n.\r\n"; //邮件内容用\r\n.\r\n结束
        $this->do_send();
           fwrite($fh,'客户端发送:'.$this->data);
        fwrite($fh,'服务器应答：'.$data1);
         
        $this->data="QUIT\r\n";
        $this->do_send();
        fwrite($fh,'客户端发送:'.$this->data);
        fwrite($fh,'服务器应答：'.$data1);
        fclose($fh); 
        socket_close($this->socket);
    }
     
    public function do_send(){  //写入文件
        socket_write($this->socket,$this->data,strlen($this->data)); //使用socket_write()函数向服务器发送命令
       /* $this->log('客户端发送:'.$this->data);
        $this->log('服务器应答：'.socket_read($this->socket,1024)).'<br>'; //使用socket_read()接受服务器的应答*/
       $data=socket_read($this->socket, 1024);
       return $data;
      /* $fh=fopen('./send_email.php','a+');
       fwrite($fh,"'客户端发送:'.$this->data"); 
       fclose($fh);*/
      }
     
    public function log($args=null,$exit=false){
        echo $args.'<br>';
                if($exit==true){exit;}
    }
}

?>
