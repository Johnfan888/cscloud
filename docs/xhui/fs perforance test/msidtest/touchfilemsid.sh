i=$1
j=$2
k=$3
basename=/data/performancetest/msid
cd $basename
START_TIME=$(date '+%s.%N')
for((m=1;m<=$i;m=m+1))
        do
		mkdir ./firstdir.$m/
                        for((n=1;n<=$j;n=n+1))
                       do
				mkdir ./firstdir.$m/seconddir.$n
				for((p=1;p<=$k;p=p+1))
					do
						touch ./firstdir.$m/seconddir.$n/file.$p
					done
                        done

        done
END_TIME=$(date '+%s.%N')

TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')

