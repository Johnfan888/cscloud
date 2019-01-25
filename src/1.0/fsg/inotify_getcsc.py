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
    # print DS[1],type(DS[1])#结果为tuple
    # print DS[1][0]#结果为字符串
    return DS


#获取每个节点所有的userID
def getUser():
    sql = "select distinct server_ip from T_UserZone Where server_ip!=''" #distinct--去重
    DS = DB(sql)
    print DS
    for i in DS:
        print i[0]
        sql="select  user_id from T_UserZone Where server_ip='%s'" %(i[0])
        userID = DB(sql)
        print userID


#获取每个节点的负载,返回每台ds排序，每个ds的oid哪个最小
def getLoad():
    sql = "select distinct server_ip from T_UserZone Where server_ip!=''" #distinct--去重
    DS = DB(sql)
    # print DS
    DS_size={}
    for i in DS:
        print i[0]
        sql = "select used_size from T_UserZone Where server_ip='%s'" %(i[0])
        used_size = DB(sql)
        # print used_size
        sum_size=0
        for j in used_size:
            sum_size += int(j[0])
        data={i[0]:sum_size}
        DS_size.update(data)
        # print Size
    DS_order= sorted(DS_size.items(), key=lambda d:d[1])#由小到大
    # print str(DS_order[1][0])
    print DS_order
    return DS_order

def getuserIDLoad(ip):
    sql = "select user_id from T_UserZone where server_ip='%s'" \
          " ORDER BY `used_size` ASC LIMIT 1" %(ip) # distinct--去重
    userID = DB(sql)
    return userID

#获取指定ip的ds所有的userID
def getDSUser(ip):
    sql = "select  user_id from T_UserZone Where server_ip='%s'" % (ip)
    userID = DB(sql)
    # print Oid
    return userID

#根据userID获取oid
def getOid(userID):
    sql = "select  email from T_User Where user_id='%s'" % (userID)
    Oid = DB(sql)
    # print Oid
    return Oid

if __name__ == '__main__':
    # getDs()
    getUser()
    # getLoad()
    # getDSUser("192.168.1.128")
    # getOid