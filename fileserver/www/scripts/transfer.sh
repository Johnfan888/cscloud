SPATH=$1
SIP=$2
SPASS=$3
DIP=$4

SCRIPT_DIR=/srv/www/htdocs/www/scripts
LOG_FILE=/var/log/csc/transferesult.txt

. $SCRIPT_DIR/csc_funcs


##touch the log file 
if [ ! -f "$LOG_FILE" ]; then
exec_ssh $DIP $SPASS  "touch "$LOG_FILE"
fi


if [ ! -d $SPATH ]; then
exec_ssh $DIP $SPASS "mkdir -p  $SPATH && echo  -e "mkdir $SPATH successful" >> $LOG_FILE
fi

##copy the remote file
exec_scp_fromremote  $SPATH/* $SIP  $SPASS  $SPATH/ && echo  -e "SIP: $SIP\nDIP: $DIP\nPATH: $SPATH\ntransfer successful\n" >> $LOG_FILE ||
{
        echo "remote copy package to $DIP failed" && exit 1
}



##delete the source file
exec_ssh $SIP $SPASS "rm -rf $SPATH/*" && echo -e  "Delete the source file successful" >> $LOG_FILE||
{
        echo "remote delete the file  failed" && exit 2
}


