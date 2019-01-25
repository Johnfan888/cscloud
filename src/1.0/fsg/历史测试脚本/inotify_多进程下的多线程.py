#!/usr/bin/python
# coding:utf-8
# author: xjl
#
import os
from pyinotify import WatchManager, Notifier, ProcessEvent, IN_DELETE, IN_CREATE, IN_MODIFY, IN_ATTRIB, IN_MOVED_TO, \
    IN_MOVED_FROM
# import pyinotify
import commands
import logging
import datetime
import threading
import multiprocessing


# 定制化事件处理类
class EventHandler(ProcessEvent):
    logging.basicConfig(level=logging.INFO, filename='/var/log/csc/cscfsg.log')  # 日志目录
    logging.info("Starting monitor...")

    def __init__(self):
        self.user = "001@163.com"
        self.ms_ip = "192.168.1.224"

    def exec_api(self, event):
        # if "swp" not in event.name:
        #     if "swx" not in event.name:
        #         if "~" not in event.name:
        print self.item_event, ":", self.filename
        print "开始上传"
        logging.info(
            "%s:%s %s" % (self.item_event, self.filename, datetime.datetime.now()))
        logging.info("开始上传 %s %s " % (self.filename, datetime.datetime.now()))
        status, output = commands.getstatusoutput(
            "php /csc/csc_client_api.php '%s' '%s' '%s' '%s'" % (
                self.method, self.filename, self.user, self.ms_ip))
        print output
        if status == 0:
            print "上传结束"
            logging.info("%s" % (output))
            logging.info("上传结束 %s %s" % (self.filename, datetime.datetime.now()))

    # 必须为process_事件名称，event表示事件对象

    # # 创建
    # def process_IN_CREATE(self, event):
    #     if "swp" not in event.name:
    #         if "swx" not in event.name:
    #             if "~" not in event.name:
    #                 self.method = "Put"
    #                 self.filename = os.path.join(event.path, event.name)
    #                 print self.filename
    #                 self.item_event = "已创建"
    #                 CR_status, CR_output = commands.getstatusoutput(
    #                     "setfattr -n user.event -v 'create' %s" % (self.filename))
    #                 if CR_status == 0:
    #                     print "属性标记为：create"
    #                     self.exec_api(event)
    #                 # threading.Thread(target=self.exec_api, args=(event,)).start()
    #                 # names=locals()
    #                 # for i in range(5):
    #                 #     names['n' + str(i)] = threading.Thread(target=self.exec_api, args=(event,))#第一个参数指定函数，第二个参数，要传递的参数
    #                 #     names['n' + str(i)].setDaemon(True)  # 设置线程为后台线程
    #                 #     names['n' + str(i)].start()
    #                 #     print  names['n' + str(i) ]
    #                 # print "okkkkkkk"

    # 删除
    def process_IN_DELETE(self, event):
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    self.method = "Delete"
                    self.filename = os.path.join(event.path, event.name)
                    self.item_event = "已删除"
                    self.exec_api(event)

    # 内容修改
    def process_IN_MODIFY(self, event):
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    self.method = "Put"
                    self.filename = os.path.join(event.path, event.name)
                    self.item_event = "内容已修改"
                    print "准备修改内容"
                    # self.exec_api(event)
                    MODIFY_status, MODIFY_output = commands.getstatusoutput(
                        "getfattr -n user.event  %s" % (self.filename))
                    if MODIFY_status == 0:
                        self.exec_api(event)
                        MODIFY1_status, MODIFY1_output = commands.getstatusoutput(
                            "setfattr -n user.event -v 'modify' %s" % (self.filename))
                        if MODIFY1_status == 0:
                            print "属性标记为：modify"
                    else:
                        print "没有此文件"

    # 属性修改
    def process_IN_ATTRIB(self, event):
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    self.method = "Put"
                    self.filename = os.path.join(event.path, event.name)
                    self.item_event = "属性已修改"

                    # self.exec_api(event)

    # # 文件移来
    # def process_IN_MOVED_TO(self, event):
    #     if "swp" not in event.name:
    #         if "swx" not in event.name:
    #             if "~" not in event.name:
    #                 self.method = "Put"
    #                 self.filename = os.path.join(event.path, event.name)
    #                 self.item_event = "文件已移动来"
    #                 # self.exec_api(event)
    #                 MOVED_status, MOVED_output = commands.getstatusoutput(
    #                     "setfattr -n user.event -v 'create' %s" % (self.filename))
    #                 if MOVED_status == 0:
    #                     print "属性标记为：create"
    #                     self.exec_api(event)

    # 文件移走
    def process_IN_MOVED_FROM(self, event):
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    self.method = "Delete"
                    self.filename = os.path.join(event.path, event.name)
                    self.item_event = "文件已移动走"
                    print self.item_event
                    self.exec_api(event)


class EventHandler_create(ProcessEvent):
    def __init__(self):
        self.OBS = ["001@163.com", "002@163.com", "003@163.com", "004@163.com", "005@163.com"]
        self.ms_ip = "192.168.1.224"
        self.nn = 0

    # 上传
    def exec_api(self, event, method, filename, item_event):
        # if "swp" not in event.name:
        #     if "swx" not in event.name:
        #         if "~" not in event.name:
        print item_event, ":", filename
        print "开始上传"
        logging.info(
            "%s:%s %s" % (item_event, filename, datetime.datetime.now()))
        logging.info("开始上传 %s %s " % (filename, datetime.datetime.now()))
        status, output = commands.getstatusoutput(
            "php /csc/csc_client_api.php '%s' '%s' '%s' '%s'" % (
                method, filename, self.OBS[0], self.ms_ip))
        print output
        if status == 0:
            print "上传结束"
            logging.info("%s" % (output))
            logging.info("上传结束 %s %s" % (filename, datetime.datetime.now()))
            CR_status, CR_output = commands.getstatusoutput(
                "setfattr -n user.event -v 'create' %s" % (filename))
            if CR_status == 0:
                print "属性标记为：create"

    # 创建
    def process_IN_CREATE(self, event, ):
        name = locals()
        print self.nn
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    method = "Put"
                    filename = os.path.join(event.path, event.name)
                    item_event = "已创建"
                    print item_event
                    name['p' + str(self.nn)] = threading.Thread(target=self.exec_api, args=(
                        event, method, filename, item_event,))  # 单个参数时候逗号必须有代表传递的是数组
                    name['p' + str(self.nn)].start()
                    self.nn += 1
                    # print self.nn
                    # print "okkkkkkkkkkkkkk"
                    # self.exec_api(event)

    # 文件移来
    def process_IN_MOVED_TO(self, event):
        name1 = locals()
        print self.nn
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    method = "Put"
                    filename = os.path.join(event.path, event.name)
                    item_event = "文件已移动来"
                    print item_event
                    name1['m' + str(self.nn)] = threading.Thread(target=self.exec_api, args=(
                        event, method, filename, item_event,))  # 逗号必须有代表传递的是数组
                    name1['m' + str(self.nn)].start()
                    self.nn += 1


def FSMonitor(path='.'):
    wm = WatchManager()  # 创建WatchManager对象
    # 创建|删除|内容修改|属性修改|移动来文件|移动走文件|
    mask = IN_DELETE | IN_MODIFY | IN_ATTRIB | IN_MOVED_FROM
    notifier = Notifier(wm, EventHandler())  # EventHandler()实例化传入,notifier会自动执行#交给Notifier进行处理
    # wm.add_watch('/tmp',pyinotify.ALL_EVENTS)#添加要监控的目录，以及要监控的事件，这里ALL_EVENT表示所有事件
    wm.add_watch(path, mask, auto_add=True, rec=True)  # 添加要监控的目录，以及要监控的事件
    # rec监控子目录
    # 自动对新添加的目录监控
    print 'now starting monitor %s' % (path)
    # notifier.loop()  # 循环监控
    while True:  # 防止阻塞？？？
        try:
            notifier.process_events()  # 同步事件
            if notifier.check_events():  # 读取事件
                notifier.read_events()
        except KeyboardInterrupt:
            notifier.stop()
            break


#


def FSMonitor_CREATE(path='.'):
    wm1 = WatchManager()  # 创建WatchManager对象
    mask1 = IN_CREATE | IN_MOVED_TO
    notifier1 = Notifier(wm1, EventHandler_create())  # EventHandler()实例化传入,notifier会自动执行#交给Notifier进行处理
    wm1.add_watch(path, mask1, auto_add=True, rec=True)  # 添加要监控的目录，以及要监控的事件
    nn = 0
    while True:
        try:
            notifier1.process_events()  # 同步事件
            if notifier1.check_events():  # 读取事件
                notifier1.read_events()
        except KeyboardInterrupt:
            notifier1.stop()
            break


if __name__ == "__main__":
    threads = []
    t1 = multiprocessing.Process(target=FSMonitor, args=('/cscdata',), name="delete")
    threads.append(t1)
    t2 = multiprocessing.Process(target=FSMonitor_CREATE, args=('/cscdata',), name="put")
    threads.append(t2)
    for t in threads:
        t.start()

    # t.join()  # 防止主线程结束，导致子线程没有运行完

    # FSMonitor('/cscdata')  # 监控的目录
