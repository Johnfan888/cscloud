#!/usr/bin/python
import MySQLdb as mdb
import os
import time
import random
import cgi
import urllib
DB_HOST=os.popen(" grep DB_HOST ../htdocs/includes/config.php | awk -F '\"' '{print $4}' ").read().strip()
DB_USER=os.popen(" grep DB_USER ../htdocs/includes/config.php | awk -F '\"' '{print $4}' ").read().strip()
DB_PWD=os.popen(" grep DB_PWD ../htdocs/includes/config.php | awk -F '\"' '{print $4}' ").read().strip()
DB_NAME=os.popen(" grep DB_NAME ../htdocs/includes/config.php | awk -F '\"' '{print $4}' ").read().strip()
def get_obsid():
    print "Content-type: text/html\n"
    form = cgi.FieldStorage()
    if form.has_key("ins_id") and form["ins_id"].value != "":
	role=int(form["ins_id"].value)
	conn=mdb.connect(DB_HOST,DB_USER,DB_PWD,DB_NAME,charset='utf8')
	cursor=conn.cursor()
	sql="select email from T_User where is_admin='%d' "%(role)
	try:
		cursor.execute(sql)
                results=cursor.fetchall()
                object_lists=[]
                for ob in  results:
                        object_lists.append(str(ob[0]))
			res='+'.join(object_lists)	
		print res
	except:
		print "fail"
    else:
	print "fail"
get_obsid()

