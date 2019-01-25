#!/usr/bin/python
# coding:utf-8
# author: xjl
import os
import pyinotify
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
class EventHandler(pyinotify.ProcessEvent):

    def __init__(self):
        self.obs = obs  # 对象集合
        self.pool = multiprocessing.Pool(processes=pool)  # 设置进程池的大小
        self.n = 0

    # 删除
    def process_IN_DELETE(self, event):
        method = "Delete"
        filename = os.path.join(event.path, event.name)
        item_event = "已删除"
        print item_event, filename

    # 内容修改
    def process_IN_MODIFY(self, event):
        method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "内容准备修改"
        # print item_event, filename

    # 属性修改
    def process_IN_ATTRIB(self, event):
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
        print item_event, filename

    # 文件移来
    def process_IN_MOVED_TO(self, event):
        method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "文件已移动来"
        print item_event, filename

    # 创建中
    def process_IN_CREATE(self, event, ):
        method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "开始创建"
        print item_event, filename

    # 可写文件被关闭(用于真正的创建)
    def process_IN_CLOSE_WRITE(self, event):
        method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "创建完成"
        print item_event, filename

    # 文件访问
    def process_IN_ACCESS(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "文件访问"
        # print item_event, ":", filename

    # 不可写文件被关闭？？
    def process_IN_CLOSE_NOWRITE(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "不可写文件被关闭"
        print item_event, ":", filename

    # 文件被打开??
    def process_IN_OPEN(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "文件被打开"
        print item_event, ":", filename

    # 文件被移动??
    def process_IN_MOVE_SELFN(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "文件被移动"
        print item_event, ":", filename

    # 后台fs被关闭??
    def process_IN_UNMOUNT(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "后台被关闭"
        print item_event, ":", filename

    # 事件队列溢出??
    def process_IN_Q_OVERFLOW(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "事件队列溢出"
        print item_event, ":", filename

    # 文件被忽略??
    def process_IN_IGNORED(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "文件被忽略"
        print item_event, ":", filename

    # 监控目录??
    def process_IN_ONLYDIR(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "监控目录"
        print item_event, ":", filename

    # 不要遵循链接符号??
    def process_IN_DONT_FOLLOW(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "不要遵循链接符号"
        print item_event, ":", filename

    # 事件在从监视目录取消链接之后不会为子节点生成??
    def process_IN_EXCL_UNLINK(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "取消链接"
        print item_event, ":", filename

    # 添加到已经存在的手表（内核2.6.14中新增的）的掩码中。??
    def process_IN_MASK_ADD(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "添加到已经存在的手表"
        print item_event, ":", filename

    # 目录??
    def process_IN_ISDIR(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "目录"
        print item_event, ":", filename

    # 监控一次？？
    def process_IN_ONESHOT(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "监控一次"
        print item_event, ":", filename

    # IN_DELETE_SELF？？
    def process_IN_DELETE_SELF(self, event):
        # method = "Put"
        filename = os.path.join(event.path, event.name)
        item_event = "IN_DELETE_SELF"
        print item_event, ":", filename


# 监控其他
def FSMonitor(path='.'):
    wm = pyinotify.WatchManager()  # 创建WatchManager对象
    # 所有事件
    mask = pyinotify.ALL_EVENTS
    notifier = pyinotify.Notifier(wm, EventHandler())  # EventHandler()实例化传入,notifier会自动执行#交给Notifier进行处理
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
