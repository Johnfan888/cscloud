num=$1
third=$2
base_path=/data/s3	
cd $base_path

START_TIME=$(date '+%s.%N')
for ((i=1;i<=$third;i=i+1))
do
     rm -rf file.$i
done
END_TIME=$(date '+%s.%N')

TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
echo rm $third files under the policy of swift>> /data/rmfiles3.log
echo The number of the file:$num >>/data/rmfiles3.log
echo Elapsed Time:$TIME_RUN>>/data/rmfiles3.log

