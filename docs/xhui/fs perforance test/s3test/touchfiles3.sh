i=$1

basename=/data/s3
cd $basename
START_TIME=$(date '+%s.%N')
for((m=1;m<=$i;m=m+1))
        do
		touch file.$m

        done
END_TIME=$(date '+%s.%N')

TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')

