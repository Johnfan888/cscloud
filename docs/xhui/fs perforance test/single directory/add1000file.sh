basepath='/data/single'
cd $basepath
START_TIME=$(date '+%s.%N')

for ((i=500001;i<=501000;i=i+1))
do
        touch file.$i
done
END_TIME=$(date '+%s.%N')

TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
echo Add 1000 files when the number of the file up to 1000 under a directory >> /data/add1000file.log
echo Elapsed Time:$TIME_RUN >> /data/add1000file.log
