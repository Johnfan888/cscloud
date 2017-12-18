<?php
final class mail{
    private $from=null,$pass=null,$to=null;
    private $smtp=null,$port=null;
    private $socket=null;
    private $data=null;   
    public function __construct(){//���캯��   
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
        $this->socket=socket_create(AF_INET,SOCK_STREAM,getprotobyname('tcp')); //�����뺯�����ӵľ��
        if(!$this->socket){
            $this->log('����socketʧ��',true); 
        }
         else{
         	$this->log('����socket�ɹ�');
        
         }
     	if(socket_connect($this->socket,$this->smtp,$this->port)){ //ʹ�þ������Ӧ���ʼ���������������
        
            $this->log('����������Ӧ��:'.socket_strerror(socket_last_error()));
        }
        else{
            $this->log('socket����ʧ��',true);
        }
         
        $this->data="EHLO HELO\r\n"; //���ͱ�ʶ������������ʶ�Լ�
        $this->do_send();
         
        $this->data="AUTH LOGIN\r\n"; //��������AUTH LOGIN������334��Ҫ����֤
        $data1=$this->do_send();
        fwrite($fh,'�ͻ��˷���:'.$this->data);
        fwrite($fh,'������Ӧ��'.$data1);
         
        $this->data=base64_encode($this->from)."\r\n"; //����base64����֮����û���
        $this->do_send();
        fwrite($fh,'�ͻ��˷���:'.$this->data);
        fwrite($fh,'������Ӧ��'.$data1);
          
        $this->data=base64_encode($this->pass)."\r\n"; //����base64����֮������룬����������235
        $this->do_send();
        fwrite($fh,'�ͻ��˷���:'.$this->data);
        fwrite($fh,'������Ӧ��'.$data1);
           
        $this->data="MAIL FROM:<".$this->from.">\r\n";// ��¼�ɹ�֮�󣬷�������MAIL FROM:< >������������250���ʼ�������
        $this->do_send();
        fwrite($fh,'�ͻ��˷���:'.$this->data);
        fwrite($fh,'������Ӧ��'.$data1);
          
        $this->data="RCPT TO:<".$this->to.">\r\n"; //��������RCPT TO:< >������������250���ʼ�������
        $this->do_send();
        fwrite($fh,'�ͻ��˷���:'.$this->data);
        fwrite($fh,'������Ӧ��'.$data1);
         
        $this->data="DATA\r\n"; //��������DATA������������354��֮����Է����ʼ�������
        $this->do_send();
        fwrite($fh,'�ͻ��˷���:'.$this->data);
        fwrite($fh,'������Ӧ��'.$data1);
         
        $this->data="From:����<".$this->from.">\r\n"; 
        $this->data.="Subject:".$header."\r\n\r\n"; //�ڴ������棬û����һ�������\r\n�������롣 
        $this->data.=$body."\r\n.\r\n"; //�ʼ�������\r\n.\r\n����
        $this->do_send();
           fwrite($fh,'�ͻ��˷���:'.$this->data);
        fwrite($fh,'������Ӧ��'.$data1);
         
        $this->data="QUIT\r\n";
        $this->do_send();
        fwrite($fh,'�ͻ��˷���:'.$this->data);
        fwrite($fh,'������Ӧ��'.$data1);
        fclose($fh); 
        socket_close($this->socket);
    }
     
    public function do_send(){  //д���ļ�
        socket_write($this->socket,$this->data,strlen($this->data)); //ʹ��socket_write()�������������������
       /* $this->log('�ͻ��˷���:'.$this->data);
        $this->log('������Ӧ��'.socket_read($this->socket,1024)).'<br>'; //ʹ��socket_read()���ܷ�������Ӧ��*/
       $data=socket_read($this->socket, 1024);
       return $data;
      /* $fh=fopen('./send_email.php','a+');
       fwrite($fh,"'�ͻ��˷���:'.$this->data"); 
       fclose($fh);*/
      }
     
    public function log($args=null,$exit=false){
        echo $args.'<br>';
                if($exit==true){exit;}
    }
}

?>
