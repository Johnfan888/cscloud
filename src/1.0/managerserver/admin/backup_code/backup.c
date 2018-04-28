#include <stdio.h> 
#include <time.h> 
#include <stdlib.h> 
#include "inifile.h"

void init_daemon(void);

#define BUF_SIZE 256

main() 
{ 
	// read configration file
	const char *file = "../config/config.txt";
	const char *section = "session";
	const char *name_key = "Time_interval";
	char name[BUF_SIZE] = {0};
	if(!read_profile_string(section, name_key, name, BUF_SIZE, "", file))
	{
		printf("read config file failure\n");
	}
	else
	{
		printf("%s=%d\n", name_key, atoi(name));
	}

	FILE *fp;
	time_t t; 
	init_daemon(); 

	while(1)
	{
		//sleep(atoi(name) * 60); 
		sleep(5); 
		//system("/usr/bin/sudo /usr/bin/php /srv/www/htdocs/backup.php");
		system("/usr/bin/sudo /usr/bin/php /srv/www/htdocs/manage/csc_manage_replicate.php");
	}
}
