num=$1
first=$2
second=$3
third=$4

base_path=/data/performancetest/msid/
START_TIME=$(date '+%s.%N')
cd $base_path
ls -alR | grep '^-'|wc -l >> /dev/zero
END_TIME=$(date '+%s.%N')
TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
sync;echo 1 >/proc/sys/vm/drop_caches
echo list all the files with msid policy >> /data/performancetest/lsfilemsid.log
echo The number of the file:$num >>/data/performancetest/lsfilemsid.log
echo The structure of the directory $first,$second,$third >>/data/performancetest/lsfilemsid.log
echo Elapsed Time:$TIME_RUN>>/data/performancetest/lsfilemsid.log
