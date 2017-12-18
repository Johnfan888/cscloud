num=$1
first=$2
second=$3
third=$4
base_path=/data/performancetest/msid
cd $base_path

START_TIME=$(date '+%s.%N')
cd  firstdir.4/seconddir.4/
for ((i=1;i<=$third;i=i+1))
do
     rm -rf file.$i
done
END_TIME=$(date '+%s.%N')

TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
echo rm $third files under the policy of msid>> /data/performancetest/rmfilemsid.log
echo The number of the file:$num >>/data/performancetest/rmfilemsid.log
echo The structure of the directory $first,$second,$third >>/data/performancetest/rmfilemsid.log
echo Elapsed Time:$TIME_RUN>>/data/performancetest/rmfilemsid.log

