basepath='/data/single'
cd $basepath
START_TIME=$(date '+%s.%N')

for ((i=1;i<=500000;i=i+1))
do
	touch file.$i
done
END_TIME=$(date '+%s.%N')

TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
echo Storyed all files under a single directory >> /data/touchonefile.log
echo The number of the file: 500000 >> /data/touchonefile.log
echo Elapsed Time:$TIME_RUN >> /data/touchonefile.log
