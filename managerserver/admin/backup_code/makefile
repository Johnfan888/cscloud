backup:backup.o inifile.o init.o
	gcc -o backup backup.o inifile.o init.o
backup.o:backup.c inifile.h 
	gcc -c backup.c
inifile.o:inifile.c inifile.h 
	gcc -c inifile.c
init.o:init.c
	gcc -c init.c
clean:
	rm -f *.o backup


