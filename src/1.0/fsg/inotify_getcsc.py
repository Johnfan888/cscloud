#!/usr/bin/python
# coding:utf-8
# author: xjl
# 获取csc的信息
import MySQLdb
# 导入配置文件
import ConfigParser

cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')
ms_ip = cf.get('csc', 'ms_ip')
MySQLport=int(cf.get('db','db_port'))
MySQLuser=cf.get('db','db_user')
MySQLpasswd=cf.get('db','db_passwd')
MySQLname=cf.get('db','db_name')

#连接数据库
def DB(sql):
    conn = MySQLdb.connect(
        host=ms_ip,
        port=MySQLport,
        user=MySQLuser,
        passwd=MySQLpasswd,
        db=MySQLname,
        charset='utf8',
    )
    cur = conn.cursor()
    cur.execute(sql)
    conn.commit()
    result = cur.fetchall()  # 取到查询结果
    cur.close()
    conn.close()
    return result

# 获取节点
def getDs():
    sql="select server_ip from T_Server"
    DS=DB(sql)
    print DS[1],type(DS[1])#结果为tuple
    print DS[1][0]#结果为字符串
#获取每个节点所有的iod
def getOid():
    sql = "select distinct server_ip from T_UserZone Where server_ip!=''" #distinct--去重
    DS = DB(sql)
    print DS
    for i in DS:
        print i[0]
        sql="select  user_id from T_UserZone Where server_ip='%s'" %(i[0])
        userID = DB(sql)
        print userID
#获取每个节点的负载
def getLoad():
    sql = "select distinct server_ip from T_UserZone Where server_ip!=''" #distinct--去重
    DS = DB(sql)
    print DS
    for i in DS:
        print i[0]
        sql = "select used_size from T_UserZone Where server_ip='%s'" %(i[0])
        usedSize = DB(sql)
        print usedSize
        Size=0
        for j in usedSize:
            Size += int(j[0])
        print Size

if __name__ == '__main__':
    getDs()
    getOid()
    getLoad()
