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
import json
import time

cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')

ms_ip = cf.get('csc', 'ms_ip')
obs = cf.get('csc', 'obs')
obs = obs.split(',')
pool = int(cf.get('csc', 'pool'))
FSMonitor_path = cf.get('path', 'FSMonitor')

import multiprocessing
import threading
import time


# 定制化事件处理类
class EventHandler(pyinotify.ProcessEvent):

    def __init__(self):
        pass

    def move(self, filename):
        time.sleep(0.5)#等待move_to
        if self.to == 1:  # 下一次事件是move_to的话会触发
            print  "对比", filename, self.filename_new
            time.sleep(60)
            # self.form = 1
            # self.to = None
        else:
            print "delete"
        self.to = None

        # 文件移走

    def process_IN_MOVED_FROM(self, event):

        filename = os.path.join(event.path, event.name)
        method = "Delete"
        item_event = "文件已移动走"
        self.t = threading.Thread(target=self.move, args=(filename,))  # 线程共享内存，变量可以直接用，进程不可以
        self.t.start()
        data_from = {'item_event': item_event, 'filename': filename}
        print json.dumps(data_from, encoding="UTF-8", ensure_ascii=False)
        # self.form = None

    # 文件移来
    def process_IN_MOVED_TO(self, event):

        self.to = 1
        print self.to
        filename = os.path.join(event.path, event.name)
        self.filename_new = filename

        if self.t.is_alive():  # 上一次事件是move_from的话，这个进程会存在。
            print "不创建"
            self.t.join()#阻塞程序，整个监控都会等待move的操作
            # self.to = None
        else:
            method = "Put"
            item_event = "文件已移动来"
            data_to = {'item_event': item_event, 'filename': filename}
            print json.dumps(data_to, encoding="UTF-8", ensure_ascii=False)
            print "创建"
            self.to = None


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
                # print notifier.read_events().fname
                # print notifier.proc_fun()
        except KeyboardInterrupt:
            notifier.stop()
            break


if __name__ == "__main__":
    FSMonitor(FSMonitor_path)  # 监控的目录
