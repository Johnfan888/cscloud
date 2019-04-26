#!/usr/bin/python
# coding:utf-8
# author: xjl
#查询数据库
import sys
import MySQLdb

# 连接数据库
def DB(sql):
    conn = MySQLdb.connect(
        host='localhost',
        port=3306,
        user='root',
        passwd='111111',
        db='configer',
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
def obs_limit():
    sql = "select group_concat(\"\",ip_address,\":\",obsid_limit_small,\":\",obsid_limit_big,\"\") from ip_table where `status`='file'"
    DS = DB(sql)[0][0]
    # print DS
    # print DS[1],type(DS[1])#结果为tuple
    # print DS[1][0]#结果为字符串
    return DS



# getUser()
if __name__ == '__main__':
    fun = sys.argv[1]
    eval(fun)  # eval() 函数用来执行一个字符串表达式

# print method
# aa=method
# print method,type(method)
# # parameter = sys.argv[2]
# main(method)



