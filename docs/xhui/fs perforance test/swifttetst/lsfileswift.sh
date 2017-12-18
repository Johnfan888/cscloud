num=$1
first=$2
second=$3

base_path=/data/swift
cd $base_path
START_TIME=$(date '+%s.%N')
ls -alR | grep '^-'|wc -l >> /dev/zero
END_TIME=$(date '+%s.%N')
TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
sync;echo 1 >/proc/sys/vm/drop_caches
echo list all the files with swift policy >> /data/lsfileswift.log
echo The number of the file:$num >>/data/lsfileswift.log
echo The structure of the directory $first,$second >>/data/lsfileswift.log
echo Elapsed Time:$TIME_RUN>>/data/lsfileswift.log
