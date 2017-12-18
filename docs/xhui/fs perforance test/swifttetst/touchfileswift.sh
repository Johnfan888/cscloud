i=$1
j=$2

basename=/data/swift
cd $basename
START_TIME=$(date '+%s.%N')
for((m=1;m<=$i;m=m+1))
        do
		mkdir ./firstdir.$m/
                        for((n=1; n<=$j;n=n+1))
                       do
			    touch ./firstdir.$m/file.$n
                        done

        done
END_TIME=$(date '+%s.%N')

TIME_RUN=$(awk 'BEGIN{print '$END_TIME' - '$START_TIME'}')

