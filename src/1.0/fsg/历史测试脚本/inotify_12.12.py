#!/usr/bin/python
# coding:utf-8
# author: xjl
import os
from pyinotify import WatchManager, Notifier, ProcessEvent, IN_DELETE, IN_CREATE, IN_MODIFY, IN_ATTRIB, IN_MOVED_TO, \
    IN_MOVED_FROM, IN_CLOSE_WRITE, IN_ACCESS, IN_CLOSE_NOWRITE,IN_OPEN
# import pyinotify
import commands
import logging
import datetime
# import threading
import multiprocessing
# 导入配置文件
import ConfigParser
import urllib

cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')

ms_ip = cf.get('csc', 'ms_ip')
obs = cf.get('csc', 'obs')
obs = obs.split(',')
pool = int(cf.get('csc', 'pool'))
FSMonitor_path = cf.get('path', 'FSMonitor')


# 定制化事件处理类
class EventHandler(ProcessEvent):
    logging.basicConfig(level=logging.INFO, filename='/var/log/csc/cscfsg.log')  # 日志目录
    logging.info("Starting monitor...")

    def __init__(self):
        self.obs = obs  # 对象集合
        self.pool = multiprocessing.Pool(processes=pool)  # 设置进程池的大小
        self.n = 0

    # 删除
    def process_IN_DELETE(self, event):
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    method = "Delete"
                    filename = os.path.join(event.path, event.name)
                    item_event = "已删除"
                    logging.info("%s %s %s" % (filename, item_event, datetime.datetime.now()))
                    data = {'method': method, 'filename': filename, 'item_event': item_event}
                    exec_api(data)

    # 内容修改
    def process_IN_MODIFY(self, event):
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    method = "Put"
                    filename = os.path.join(event.path, event.name)
                    item_event = "内容准备修改"
                    logging.info("%s %s %s" % (filename, item_event, datetime.datetime.now()))
                    data = {'method': method, 'filename': filename, 'item_event': item_event, 'oid': None}
                    MODIFY_status, MODIFY_output = commands.getstatusoutput(
                        "getfattr -n user.event  '%s' --only-values  --absolute-names" % (filename))
                    if MODIFY_status == 0:
                        if MODIFY_output == "created" or MODIFY_output == "modify":
                            exec_api(data)
                        # else:
                        #     print "文件存在,正在创建"
                    else:
                        print "文件不存在，运行失败"
                        logging.info("文件不存在，运行失败 %s %s %s" % (filename, item_event, datetime.datetime.now()))

    # 属性修改
    def process_IN_ATTRIB(self, event):
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    method = "Put"
                    filename = os.path.join(event.path, event.name)
                    item_event = "属性已修改"
                    logging.info("%s %s %s" % (filename, item_event, datetime.datetime.now()))
                    print item_event

    # 文件移走
    def process_IN_MOVED_FROM(self, event):

        method = "Delete"
        filename = os.path.join(event.path, event.name)
        item_event = "文件已移动走"
        print item_event

    # 文件移来
    def process_IN_MOVED_TO(self, event):
        method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "文件已移动来"
        print item_event

    # 创建中
    def process_IN_CREATE(self, event, ):
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    method = "Put"
                    filename = os.path.join(event.path, event.name)
                    item_event = "开始创建"
                    logging.info("%s %s %s" % (filename, item_event, datetime.datetime.now()))
                    CR_status, CR_output = commands.getstatusoutput(
                        "setfattr -n user.event -v 'creating' '%s'" % (filename))
                    if CR_status == 0:
                        print "文件正在创建，属性标记为：creating"
                        logging.info("文件正在创建，属性标记为：creating %s %s %s" % (filename, item_event, datetime.datetime.now()))
                    else:
                        print "文件不存在，运行失败"
                        logging.info("文件不存在，运行失败 %s %s %s" % (filename, item_event, datetime.datetime.now()))

    # 可写文件被关闭(用于真正的创建)
    def process_IN_CLOSE_WRITE(self, event):
        method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "创建完成"
        logging.info("%s %s %s" % (filename, item_event, datetime.datetime.now()))
        CW_status, CW_output = commands.getstatusoutput(
            "getfattr -n user.event  '%s' --only-values  --absolute-names" % (filename)
            # --only-values:只输出属性
            # --absolute-names：默认去掉/,加上后不显示：getfattr: Removing leading '/' from absolute path names
        )
        if CW_status == 0:
            if CW_output == "creating":
                data = {'method': method, 'filename': filename, 'item_event': item_event, 'oid': self.obs[self.n]}
                self.pool.apply_async(func=exec_api,
                                      args=(data,))  # 加入池
                self.n += 1
                if self.n > (len(self.obs) - 1):
                    self.n = 0
            # else:
            #     print "文件存在，属性不是creating"
        else:
            print "文件不存在，运行失败"
            logging.info("文件不存在，运行失败 %s %s %s" % (filename, item_event, datetime.datetime.now()))

    # 文件访问
    def process_IN_ACCESS(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "文件访问"
        print item_event, ":", filename

    # 不可写文件被关闭？？
    def process_IN_CLOSE_NOWRITE(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "不可写文件被关闭"
        print item_event, ":", filename

    # 文件被打开？？
    def process_IN_OPEN(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "文件被打开"
        print item_event, ":", filename

def exec_api(data):
    # 删除
    if data['method'] == "Delete":
        EXEC_status, EXEC_output = commands.getstatusoutput(
            "python /csc/inotify_delete.py '%s' '%s' '%s' '%s' " % (
                data['method'], data['filename'], data['item_event'], ms_ip))

    # 创建|修改
    if data['method'] == "Put":
        if data['oid']:  # 创建
            EXEC_status, EXEC_output = commands.getstatusoutput(
                "python /csc/inotify_create.py '%s' '%s' '%s' '%s' '%s'" % (
                    data['method'], data['filename'], data['item_event'], ms_ip, data['oid']))
        else:  # 内容修改
            EXEC_status, EXEC_output = commands.getstatusoutput(
                "python /csc/inotify_modify.py '%s' '%s' '%s' '%s' " % (
                    data['method'], data['filename'], data['item_event'], ms_ip))

    # 元数据修改
    if data['method'] == "Post":
        EXEC_status, EXEC_output = commands.getstatusoutput(
            "python /csc/inotify_rename.py '%s' '%s' '%s' '%s' '%s'" % (
                data['method'], data['filename'], data['item_event'], ms_ip))

    if EXEC_status == 0:
        print "api操作成功"
        print EXEC_output
    else:
        print "api操作失败"


# exec_api放在全局
'''
这个不放在EventHandler_create类中的原因：
pool方法都使用了queue.Queue将task传递给工作进程。multiprocessing必须将数据序列化以在进程间传递。
方法只有在模块的顶层时才能被序列化，跟类绑定的方法不能被序列化，就会出现上面的异常。
解决方法:
1.用线程替换进程
2.可以使用copy_reg来规避上面的异常.
3.dill 或pathos.multiprocesssing ：use pathos.multiprocesssing, instead of multiprocessing. pathos.multiprocessing is a fork of multiprocessing that uses dill. dill can serialize almost anything in python, so you are able to send a lot more around in parallel.
'''


# 监控其他
def FSMonitor(path='.'):
    wm = WatchManager()  # 创建WatchManager对象
    # |删除|内容修改|属性修改|移动来文件|创建|移动走|可写文件被关闭|文件被访问
    mask = IN_DELETE | IN_MODIFY | IN_ATTRIB | IN_MOVED_FROM | IN_CREATE | IN_MOVED_TO | IN_CLOSE_WRITE | IN_ACCESS \
           | IN_CLOSE_NOWRITE|IN_OPEN
    notifier = Notifier(wm, EventHandler())  # EventHandler()实例化传入,notifier会自动执行#交给Notifier进行处理
    # wm.add_watch('/tmp',pyinotify.ALL_EVENTS)#添加要监控的目录，以及要监控的事件，这里ALL_EVENT表示所有事件
    wm.add_watch(path, mask, auto_add=True, rec=True)  # 添加要监控的目录，以及要监控的事件
    # rec监控子目录
    # 自动对新添加的目录监控
    print 'now starting monitor %s' % (path)
    # notifier.loop()  # 循环监控
    while True:
        try:
            notifier.process_events()  # 同步事件
            if notifier.check_events():  # 读取事件
                notifier.read_events()
        except KeyboardInterrupt:
            notifier.stop()
            break


if __name__ == "__main__":
    FSMonitor(FSMonitor_path)  # 监控的目录
