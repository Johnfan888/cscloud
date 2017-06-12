CREATE TABLE IF NOT EXISTS `T_User` ( 
	user_id int unsigned NOT NULL auto_increment,
	email varchar(50) NOT NULL default '',
	password char(32) NOT NULL default '',
	is_admin tinyint unsigned NOT NULL default '0',
	is_checked tinyint unsigned NOT NULL default '0',
	PRIMARY KEY (user_id)
) auto_increment=100000000 ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `T_Cache` ( 
	file_id char(32) NOT NULL default '',
	file_name varchar(255) NOT NULL default '',
	parent_id char(32) NOT NULL default '',
	parent_name varchar(255) NOT NULL default '',
	version smallint unsigned NOT NULL default '1',
	size bigint unsigned NOT NULL default '0',
	file_type tinyint unsigned NOT NULL default '0',
	modify_time int unsigned NOT NULL default '0',
	user_id int unsigned NOT NULL default '0',
	PRIMARY KEY (file_id),
	KEY uid_time (user_id, modify_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `T_FileInfo` ( 
	file_id char(32) NOT NULL default '',
	file_name varchar(255) NOT NULL default '',
	parent_id char(32) NOT NULL default '',
	parent_name varchar(255) NOT NULL default '',
	version smallint unsigned NOT NULL default '0',
	size bigint unsigned NOT NULL default '0',
	file_type tinyint unsigned NOT NULL default '0',
	modify_time int unsigned NOT NULL default '0',
	user_id int unsigned NOT NULL default '0',
	is_del tinyint unsigned NOT NULL default '0',
	PRIMARY KEY (file_id),
	KEY uid (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `T_FileLocation` ( 
	file_id char(32) NOT NULL default '',
	server_ip char(15) NOT NULL default '',
	file_path varchar(50) NOT NULL default '',
	ha_server_ip char(15) NOT NULL default '',
	ha_file_path varchar(255) NOT NULL default '',
	user_id int unsigned NOT NULL default '0',
	flag tinyint unsigned NOT NULL default '0',
	PRIMARY KEY (file_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `T_UserZone` ( 
	user_id int NOT NULL default '0',
	server_ip char(15) NOT NULL default '',
	ha_server_ip char(15) NOT NULL default '',
	useable_size bigint unsigned NOT NULL default '0',
	used_size bigint unsigned NOT NULL default '0',
	PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `T_Server` ( 
	server_id int unsigned NOT NULL auto_increment,
	server_ip char(15) NOT NULL default '',
	file_path varchar(50) NOT NULL default '',
	status tinyint unsigned NOT NULL default '1',
	PRIMARY KEY (server_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8