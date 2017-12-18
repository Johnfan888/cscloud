base_path=/data/single
START_TIME=$(date '+%s.%N')
cd $base_path
touch hxh
END_TIME=$(date '+%s.%N')
TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')
echo Touch a file when the number of files up to 1000 under the directory >> /data/addfile.log
echo The number of the file:100000 >>/data/addfile.log
echo Elapsed Time:$TIME_RUN>>/data/addfile.log

