num=$1
first=$2
second=$3
third=$4
base_path=/data/performancetest/msid
cd  $base_path

START_TIME=$(date '+%s.%N')
cd  ./firstdir.3/seconddir.3/
for((i=2000;i<=3000;i=i+1))
do
	touch file.$i
done
END_TIME=$(date '+%s.%N')
TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
sync;echo 1 >/proc/sys/vm/drop_caches

echo add 1000 files with the policy of msid>> /data/performancetest/addfilemsid.log
echo The number of the file:$num >>/data/performancetest/addfilemsid.log
echo The structure of the directory $first,$second,$third >>/data/performancetest/addfilemsid.log
echo Elapsed Time:$TIME_RUN>>/data/performancetest/addfilemsid.log
