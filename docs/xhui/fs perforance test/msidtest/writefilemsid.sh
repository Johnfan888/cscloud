num=$1
first=$2
second=$3
third=$4
basepath='/data/performancetest/msid'
cd $basepath

START_TIME=$(date '+%s.%N')
cd ./firstdir.12/seconddir.12/
for((i=1;i<100;i=i+1))
do	
	for((j=1;j<=$third;j=j+1))
	do
	echo hello > file.$j
	done
	sync; echo 1 > /proc/sys/vm/drop_caches
done
END_TIME=$(date '+%s.%N')

TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')

echo write data to $third files 100 times with msid policy >> /data/performancetest/writefilemsid.log
echo The  total number of the file is $num: >> /data/performancetest/writefilemsid.log
echo The structure of the directory $first,$second,$third >> /data/performancetest/writefilemsid.log
echo Elapsed Time:$TIME_RUN >> /data/performancetest/writefilemsid.log
