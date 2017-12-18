base_path=/data/single
START_TIME=$(date '+%s.%N')
cd $base_path
for ((i=1;i<1000;i=i+1))
	do
		echo hello > file.$i
	done
sync;echo 3 >/proc/sys/vm/drop_caches
END_TIME=$(date '+%s.%N')
TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
echo write data to 1000 files when the number of files up to 10000 under the directory >> /data/write.log
echo Elapsed Time:$TIME_RUN>>/data/write.log

