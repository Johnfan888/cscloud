num=$1
first=$2
second=$3
third=$4
basepath='/data/performancetest/msid'
cd $basepath

START_TIME=$(date '+%s.%N')
cd  ./firstdir.1/seconddir.1/
for((i=1;i<100;i=i+1))
do
	for((j=1;j<=$third;j=j+1))
	do
	cat  file.$j
	done
	sync; echo 1 > /proc/sys/vm/drop_caches
done
END_TIME=$(date '+%s.%N')

TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')

echo cat $third files 100 times with msid policy >> /data/performancetest/catfilemsid.log
echo The total number of the files is $num >> /data/performancetest/catfilemsid.log
echo The structure of the directory $first,$second,$third >>/data/performancetest/catfilemsid.log
echo Elapsed Time:$TIME_RUN >> /data/performancetest/catfilemsid.log
