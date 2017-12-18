num=$1
third=$2
basepath='/data/s3'
cd $basepath

START_TIME=$(date '+%s.%N')
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

echo write data to $third files 100 times with s3 policy >> /data/writefiles3.log
echo The  total number of the file is $num: >> /data/writefiles3.log
echo Elapsed Time:$TIME_RUN >> /data/writefiles3.log
