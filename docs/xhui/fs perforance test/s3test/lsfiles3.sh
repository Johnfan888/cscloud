num=$1
base_path=/data/s3
cd $base_path
START_TIME=$(date '+%s.%N')
ls -alR | grep '^-'|wc -l >> /dev/zero
END_TIME=$(date '+%s.%N')
TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
sync;echo 1 >/proc/sys/vm/drop_caches
echo list all the files with swift s3>> /data/lsfiles3.log
echo The number of the file:$num >>/data/lsfiles3.log
echo Elapsed Time:$TIME_RUN>>/data/lsfiles3.log
