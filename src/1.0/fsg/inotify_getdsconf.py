#!/usr/bin/python
# coding:utf-8
# author: xjl
# 获取ds的一些信息
import MySQLdb
# 导入配置文件
import ConfigParser
from inotify_conf import obsid_limit_small,obsid_limit_big
import inotify_http_getcsc
cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')


# 写入配置文件
def w_conf(A, B, C):
    cf.add_section(A)
    cf.set(A, 'obsid_limit_small', B)
    cf.set(A, 'obsid_limit_big', C)
    cf.write(open("/csc/csc.conf", "w"))

if __name__ == '__main__':
    DS=inotify_http_getcsc.main(0,"obs_limit()")
    print DS
    DS=DS.split(",")
    print DS
    obsid_small_sum = 0
    obsid_big_sum = 0
    for i in DS:
        i = i.split(":")
        print (i[0]), i[1], i[2]
        A = str(i[0])
        if i[1] == None:
            B = obsid_limit_small
        else:
            B = str(i[1])

        if i[2] == None:
            C = obsid_limit_big
        else:
            C = str(i[2])
        # print type(A),type(B),type(C)
        print "-----"
        obsid_small_sum += int(B)
        obsid_big_sum += int(C)
        w_conf(A, B, C)
    # print     obsid_big_sum,obsid_small_sum
    cf.set('csc', 'obsid_big_sum', obsid_big_sum)
    cf.set('csc', 'obsid_small_sum', obsid_small_sum)
    cf.write(open("/csc/csc.conf", "w"))
