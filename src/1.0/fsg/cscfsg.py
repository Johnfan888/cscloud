#!/usr/bin/python
# coding:utf-8
# author: xjl
import os
from pyinotify import WatchManager, Notifier, ProcessEvent, IN_DELETE, IN_CREATE, IN_MODIFY, IN_ATTRIB, IN_MOVED_TO, \
    IN_MOVED_FROM
# import pyinotify
import commands
import logging
import datetime
import urllib


# 定制化事件处理类
class EventHandler(ProcessEvent):
    logging.basicConfig(level=logging.INFO, filename='/var/log/csc/fsg/fsglog')#日志目录
    logging.info("Starting monitor...")

    def __init__(self):
        self.user = "zfan@istl.chd.edu.cn"
        self.ms_ip = "192.168.190.130"

    def exec_api(self, event):
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    print self.item_event, ":", self.filename
                    logging.info(
                        "%s:%s %s" % (self.item_event, self.filename, datetime.datetime.now()))
                    status, output = commands.getstatusoutput(
                        "php /home/zfan/cscfsg/csc_client_api.php '%s' '%s' '%s' '%s'" % (
                            self.method, urllib.quote(self.filename), self.user, self.ms_ip))
                    if status == 0:
                        print "开始上传"
                    print output
		logging.info("%s" % (output))

    # 必须为process_事件名称，event表示事件对象

    # 创建
    def process_IN_CREATE(self, event):
        self.method = "Put"
        self.filename = os.path.join(event.path, event.name)
        self.item_event = "已创建"
        self.exec_api(event)

    # 删除
    def process_IN_DELETE(self, event):
        self.method = "Delete"
        self.filename = os.path.join(event.path, event.name)
        self.item_event = "已删除"
        self.exec_api(event)

    # 内容修改
    def process_IN_MODIFY(self, event):
        self.method = "Put"
        self.filename = os.path.join(event.path, event.name)
        self.item_event = "内容已修改"
        self.exec_api(event)

    # 属性修改
    def process_IN_ATTRIB(self, event):
        self.method = "Put"
        self.filename = os.path.join(event.path, event.name)
        self.item_event = "属性已修改"
        self.exec_api(event)

    # 文件移走
    def process_IN_MOVED_TO(self, event):
        self.method = "Delete"
        self.filename = os.path.join(event.path, event.name)
        self.item_event = "文件已移动来"
        self.exec_api(event)

    # 文件移动来
    def process_IN_MOVED_FROM(self, event):
        self.method = "Put"
        self.filename = os.path.join(event.path, event.name)
        self.item_event = "文件已移动走"
        self.exec_api(event)


def FSMonitor(path='.'):
    wm = WatchManager()  # 创建WatchManager对象
    # 创建|删除|内容修改|属性修改|移动来文件|移动走文件|
    mask = IN_CREATE | IN_DELETE | IN_MODIFY | IN_ATTRIB | IN_MOVED_TO | IN_MOVED_FROM
    notifier = Notifier(wm, EventHandler())  # EventHandler()实例化传入,notifier会自动执行#交给Notifier进行处理
    # wm.add_watch('/tmp',pyinotify.ALL_EVENTS)#添加要监控的目录，以及要监控的事件，这里ALL_EVENT表示所有事件
    wm.add_watch(path, mask, auto_add=True, rec=True)  # 添加要监控的目录，以及要监控的事件
    # rec监控子目录
    # 自动对新添加的目录监控
    print 'now starting monitor %s' % (path)
    notifier.loop()  # 循环监控
    # while True:  # 防止阻塞？？？
    #     try:
    #         notifier.process_events()  # 同步事件
    #         if notifier.check_events():  # 读取事件
    #             notifier.read_events()
    #     except KeyboardInterrupt:
    #         notifier.stop()
    #         break


if __name__ == "__main__":
    FSMonitor('/cscdata')#监控的目录
