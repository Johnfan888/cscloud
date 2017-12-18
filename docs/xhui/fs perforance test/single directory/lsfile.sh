base_path=/data/single
START_TIME=$(date '+%s.%N')
cd $base_path
ls -al | grep '^-'|wc -l >> /dev/zero
END_TIME=$(date '+%s.%N')
TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
sync;echo 1 >/proc/sys/vm/drop_caches
echo list all the files under the single directory >> /data/lsfile.log
echo The number of the file:100000 >>/data/lsfile.log
echo Elapsed Time:$TIME_RUN>>/data/lsfile.log

