base_path=/data/single
START_TIME=$(date '+%s.%N')
cd $base_path
for((i=1000;i<=2000;i=i+1))
do
        rm -rf /data/single/file.$i
done
END_TIME=$(date '+%s.%N')
TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
echo delete 1000 files >> /data/rmfile.log
echo The number of the total file:1000000 >>/data/rmfile.log
echo Elapsed Time:$TIME_RUN>>/data/rmfile.log
