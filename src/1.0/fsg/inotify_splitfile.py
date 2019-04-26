#!/usr/bin/python
# coding:utf-8
# author: xjl
# 切分大文件
import sys
import commands

import os
import multiprocessing
# import inotify_getcsc
#导入配置文件
from inotify_conf import ms_ip,pool,small_size,obsid_big_sum
import inotify_http_getcsc



# 拆分大文件，并标注
def split():
    SP_status, SP_output = commands.getstatusoutput(
        "mkdir '%s/%s' &&  split -b %s '%s' '%s/%s/%s'_split   " % (
        filepath, fname, small_size, filename, filepath, fname, fullflname))
    if SP_status == 0:
        print "拆分成功"
        SP1_status, SP1_output = commands.getstatusoutput(
            "setfattr -n user.event -v 'splited' %s/%s/* " % (filepath, fname))
        if SP1_status == 0:
            print "文件标记为拆分文件"
        SP2_status, SP2_output = commands.getstatusoutput(
            "setfattr -n user.file -v 'big' '%s'" % (filename))
        if SP2_status == 0:
            print "文件标记为大文件"
        # SP3_status, SP3_output = commands.getstatusoutput(
        #     "setfattr -n user.file -v 'big' %s/%s/* " % (filepath,fname))
        # if SP3_status == 0:
        #     print "拆分文件标记为大文件"


# 计算几个文件轮询，几个文件选择最小
def count():
    file_num = (file_size + small_size - 1) / small_size  # 取整，即算出切分了多少个文件
    # ds_limit_num = int(file_num * split_sum)  # 针对切分文件，每台ds上最多容纳的切分文件数量,int为了取整
    # b=file_size%small_size#求余数，、
    roll = file_num / obsid_big_sum  # 轮询几次，做选择oid
    remain = file_num % obsid_big_sum  # 剩下几个，做指定oid
    return file_num, roll, remain


def filename_small():
    EX_status, EX_output = commands.getstatusoutput(
        "readlink -f %s/%s/*" % (filepath, fname))
    # print EX_output
    if EX_status == 0:
        filename_s = EX_output.split('\n')  # 所有的切分文件，（输出会是换行，所以分割成list）
        # print EX_output
    return filename_s


def select():
    q = count()  # 得到a,b,c（文件总数，轮询几次，剩下几个文件）
    print q
    roll = q[1]  # 轮询次数
    k = 0  # 选取切分文件的name
    if roll != 0:  # 可以轮询一次
        for i in range(roll):
            for j in DS:
                # print j
                # user_id = inotify_getcsc.getDSUser(str(j[0]))
                user_id = inotify_http_getcsc.main(1,"getDSUser('%s')" % (str(j)))  # 获取指定ip的DS所拥有的所有oid
                user_id = user_id.split(",")
                for l in user_id:
                    # print l
                    # obsid = inotify_getcsc.getOid(l[0])
                    obsid = inotify_http_getcsc.main(1,"getOid('%s')" % (l))
                    data = {'method': "Put", 'item_event': "创建", 'filename': filename_s[k],
                            'obsid': str(obsid), 'type': "big"}
                    k += 1
                    print data
                    print k
                    spool.apply_async(func=exec_api, args=(data,))
                    print "===========切分上传==============="
        # print k
        # 指定
        remain = q[2]  # 剩余文件个数
        print remain
        # user_id = inotify_getcsc.getLoad_obsid(int(remain))  # 获取负载最小的user_id
        user_id = inotify_http_getcsc.main(1,"getLoad_obsid('%s')"%(int(remain)))  # 获取负载最小的user_id
        user_id=user_id.split(",")
        for i in user_id:
            # obsid = inotify_getcsc.getOid(i[0])
            obsid = inotify_http_getcsc.main(1,"getOid('%s')" % (i))
            # print obsid[0][0]
            data = {'method': "Put", 'item_event': "创建", 'filename': filename_s[k],
                    'obsid': str(obsid), 'type': "big"}
            k += 1
            print data
            print k
            spool.apply_async(func=exec_api, args=(data,))
            print "===========切分上传==============="
        # print k
    else:  # 一次都轮询不了，直接以ds指定
        file_num = q[0]
        a = file_num / len(DS)  # 轮询几次
        b = file_num % len(DS)  # 剩余几个
        print a, b
        for i in range(a):
            for j in DS:
                # user_id = inotify_getcsc.getDSUser(str(j[0]))
                user_id = inotify_http_getcsc.main(1,"getDSUser('%s')" % (str(j)))  # 获取指定ip的DS所拥有的所有oid
                user_id = user_id.split(",")
                # obsid = inotify_getcsc.getOid(user_id[i])
                obsid = inotify_http_getcsc.main(1,"getOid('%s')" % (user_id[i]))
                # print obsid

                data = {'method': "Put", 'item_event': "创建", 'filename': filename_s[k],
                        'obsid': str(obsid), 'type': "big"}
                k += 1
                print data
                print k
                spool.apply_async(func=exec_api, args=(data,))
                print "===========切分上传==============="
        # 指定

        # user_id = inotify_getcsc.getLoad_obsid(int(b))  # 获取负载最小的user_id
        user_id = inotify_http_getcsc.main(1,"getLoad_obsid('%s')" % (int(b)))  # 获取负载最小的user_id
        user_id = user_id.split(",")
        for i in user_id:
            # obsid = inotify_getcsc.getOid(i[0])
            obsid = inotify_http_getcsc.main(1,"getOid('%s')" % (i))
            # print obsid[0][0]
            # obsid =obsid[0][0].encode('utf-8')
            data = {'method': "Put", 'item_event': "创建", 'filename': filename_s[k],
                    'obsid':str(obsid), 'type': "big"}
            k += 1
            print data
            print k
            spool.apply_async(func=exec_api, args=(data,))
            print "===========切分上传==============="
        # print k

def exec_api(data):
    # print "ok"
    EX1_status, EX1_output = commands.getstatusoutput(
        "python /csc/inotify_create.py '%s' '%s' '%s' '%s' '%s' '%s'" % (
            data['method'],data['filename'] , data['item_event'], ms_ip, data['obsid'],data['type']))
    print EX1_output

def empty():
    for i in filename_s:
        # print i
        f = open(i, 'r+')
        f.truncate()#清空文件内容
        f.close()

if __name__ == '__main__':
    filename = sys.argv[1]
    # small_size = int(sys.argv[2])
    file_size = int(sys.argv[2])

    filepath, fullflname = os.path.split(filename)
    fname, ext = os.path.splitext(fullflname)
    print filepath, fname

    split()  # 切分

    # DS = inotify_getcsc.getDs()  # 获取到所有ds
    DS = inotify_http_getcsc.main(1,"getDs()")  # 获取到所有ds
    # print type(DS)
    DS = DS.split(",")

    filename_s = filename_small()
    spool = multiprocessing.Pool(processes=pool)  # 设置进程池的大小

    select()
    spool.close()
    spool.join()  # 这里要先关闭再JOIN。进程池中进程执行完后再关闭，如果注释，那么程序直接关闭
    #清空切分文件内容??是否需要
    empty()#清空切分文件
