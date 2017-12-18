base_path=/data/single
START_TIME=$(date '+%s.%N')
cd $base_path
for((j=1;j<=1000;j++))
do
	for((i=1;i<=100;i=i+1))
	do
		cat file.$i
	done
	sync; echo 1 > /proc/sys/vm/drop_caches   
done

END_TIME=$(date '+%s.%N')
TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
echo read 1000 files 100 times>> /data/catfile.log
echo The number of the total file:1000 >>/data/catfile.log
echo Elapsed Time:$TIME_RUN>>/data/catfile.log

