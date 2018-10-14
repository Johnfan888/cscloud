DROP DATABASE IF EXISTS configer;
CREATE DATABASE configer;

USE configer;

CREATE TABLE tb_member (
  id int(4) NOT NULL auto_increment,
  name varchar(20) NOT NULL,
  password varchar(50) NOT NULL,
  answer varchar(100) NOT NULL,
  question varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  realname varchar(30) NOT NULL,
  birthday date NOT NULL,
  telephone varchar(20) NOT NULL,
  qq varchar(15) NOT NULL,
  count int(1) NOT NULL default '0',
  active boolean NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=gb2312;

INSERT INTO tb_member VALUES (57,'admin','e10adc3949ba59abbe56e057f20f883e','','','162161518@qq.com','','0000-00-00','','',0,1);

CREATE TABLE ip_table(
id tinyint(2) NOT NULL auto_increment,
ip_address varchar(15) NOT NULL,
status varchar(10) NOT NULL,
cpu varchar(50) NOT NULL,
memory varchar(50) NOT NULL,
disk varchar(50) NOT NULL,
userfilepath varchar(1024),
post_size bigint NOT NULL,
loading float,
transfer_status boolean,
install_flag boolean default 0,
PRIMARY KEY  (id)
)ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=gb2312;

/*-----------*/
CREATE TABLE spare_node(
id tinyint(2) NOT NULL auto_increment,
ip_address varchar(15) NOT NULL,
status varchar(10) NOT NULL,
cpu varchar(50) NOT NULL,
memory varchar(50) NOT NULL,
disk varchar(50) NOT NULL,
userfilepath varchar(1024),
post_size bigint NOT NULL,
loading float,
transfer_status boolean,
install_flag boolean default 0,
PRIMARY KEY  (id)
)ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=gb2312;
/*-----------*/

CREATE TABLE log_transfer(
id int(4) NOT NULL auto_increment,
source_ip varchar(15) NOT NULL,
target_ip varchar(15) NOT NULL,
time varchar(20) NOT NULL,
PRIMARY KEY  (id)
)ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=gb2312;

CREATE TABLE QUEUE(
ID INT(4) NOT NULL auto_increment,
QUEUENAME VARCHAR(20) NOT NULL,
STATUS boolean NOT NULL DEFAULT '0',
PRIMARY KEY  (ID)
)ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=gb2312;

INSERT INTO QUEUE(QUEUENAME,STATUS) VALUES('TRANSFER','0');

CREATE TABLE IF NOT EXISTS MinitorItem (
mi_id int not null auto_increment, 
mi_mib varchar(50),
mi_name varchar(50) not null,
mi_shellname varchar(50) not null,
mi_shellpath varchar(100) not null,
mi_shelltime date not null,
mi_iprange varchar(50) not null,
mi_desc varchar(30),
primary key(mi_id, mi_mib)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

