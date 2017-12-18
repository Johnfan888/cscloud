num=$1
base_path=/data/s3
cd  $base_path

START_TIME=$(date '+%s.%N')
	for((i=$num+1;i<=$num+1001;i=i+1))
do
	touch file.$i
done
END_TIME=$(date '+%s.%N')
TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
sync;echo 1 >/proc/sys/vm/drop_caches

echo add 1000 files with the policy of s3>> /data/addfiles3.log
echo The number of the file:$num >>/data/addfiles3.log
echo Elapsed Time:$TIME_RUN>>/data/addfiles3.log
