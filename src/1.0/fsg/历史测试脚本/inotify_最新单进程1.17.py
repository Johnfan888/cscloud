#!/usr/bin/python
# coding:utf-8
# author: xjl
import os
import pyinotify
import commands
import logging
import datetime
import multiprocessing
# 导入配置文件
import ConfigParser
import urllib
import time
import threading
import json

cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')

ms_ip = cf.get('csc', 'ms_ip')
obs = cf.get('csc', 'obs')
obs = obs.split(',')
pool = int(cf.get('csc', 'pool'))
FSMonitor_path = cf.get('path', 'FSMonitor')
size = int(cf.get('csc', 'size'))
small_size = cf.get('csc', 'smallfile_size')

dddd = "132"


# 选择oid
# def select_oid(data):
#     global n
#     data.update({'oid': obs[n]})  # 追加oid到data的dict中
#     spool.apply_async(func=exec_api, args=(data,))  # 加入池
#     # hh=multiprocessing.Process(target=exec_api,args=(data,))
#     # hh.start()
#     n += 1
#     if n > (len(obs) - 1):
#         n = 0
#     print " -------------------------------- "


# 定制化事件处理类
class EventHandler(pyinotify.ProcessEvent):
    logging.basicConfig(level=logging.INFO, filename='/var/log/csc/cscfsg.log')  # 日志目录
    logging.info("Starting monitor...")

    def __init__(self):
        # self.obs = obs  # 对象集合
        self.t = None  # 判断mv走线程是否存在
        self.to = None  # 判断mv来事件是否存在
        self.size = size
        self.small_size = small_size
        self.n = 0  # 选择oid
        self.spool = multiprocessing.Pool(processes=pool)  # 设置进程池的大小

    # 删除
    def process_IN_DELETE(self, event):
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    method = "Delete"
                    filename = os.path.join(event.path, event.name)
                    item_event = "已删除"

                    filepath, fullflname = os.path.split(filename)
                    fname, ext = os.path.splitext(fullflname)
                    print filepath, fullflname, fname, ext
                    DE_status, DE_output = commands.getstatusoutput(
                        "ls '%s/%s'" % (filepath, fname))
                    if DE_status == 0:
                        print "删除大文件"
                        DE1_status, DE1_output = commands.getstatusoutput(
                            "rm -rf '%s/%s'" % (filepath, fname))
                        if DE1_status == 0:
                            print "大文件删除成功"
                    else:
                        logging.info("%s %s %s" % (filename, item_event, datetime.datetime.now()))
                        data = {'method': method, 'filename': filename, 'item_event': item_event}
                        exec_api(data)

    # 内容修改
    def process_IN_MODIFY(self, event):
        if "swp" not in event.name:
            if "swx" not in event.name:
                if "~" not in event.name:
                    method = "Modify"
                    filename = os.path.join(event.path, event.name)
                    item_event = "内容正在修改"
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
        if "~" not in event.name:
            method = "Delete"
            filename = os.path.join(event.path, event.name)
            item_event = "文件已移动走"
            self.t = threading.Thread(target=self.move, args=(filename,))  # 线程共享内存，变量可以直接用，进程不可以
            self.t.start()
            data_from = {'item_event': item_event, 'filename': filename}
            # print data_from
            print json.dumps(data_from, encoding="UTF-8", ensure_ascii=False)
            logging.info("%s %s %s" % (filename, item_event, datetime.datetime.now()))

    # 文件移来
    def process_IN_MOVED_TO(self, event):
        if "~" not in event.name:
            self.to = 1
            filename = os.path.join(event.path, event.name)
            self.filename_new = filename
            if self.t:
                if self.t.is_alive():  # 上一次事件是move_from的话，这个进程会存在。
                    print "不创建"
                    self.t.join()  # 阻塞程序，整个监控都会等待move的操作
            else:
                method = "Put"
                item_event = "文件已移动来"
                data_to = {'item_event': item_event, 'filename': filename}
                print json.dumps(data_to, encoding="UTF-8", ensure_ascii=False)
                logging.info("%s %s %s" % (filename, item_event, datetime.datetime.now()))
                print "创建文件"
                data = {'method': method, 'filename': filename, 'item_event': item_event, 'oid': self.obs[self.n]}
                self.select_oid(data)  # 创建
            self.to = None

    # 自定义，用于rename，关联-文件移走|文件移来
    def move(self, filename):
        time.sleep(0.5)  # 等待move_to
        if self.to == 1:  # 下一次事件是move_to的话会触发
            print  "改名", filename, self.filename_new
            rename_data = {'method': "Post", 'filename': filename, 'item_event': '改名',
                           'filename_new': self.filename_new}
            exec_api(rename_data)  # post|rename
            logging.info("改名 %s %s %s" % (filename, self.filename_new, datetime.datetime.now()))
        self.t = None

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
                        "getfattr -n user.event  '%s' --only-values  --absolute-names" % (filename))
                    # if CR_status == 0:
                    if CR_output == "downloaded":
                        return
                    else:
                        CR_status, CR_output = commands.getstatusoutput(
                            "setfattr -n user.event -v 'creating' '%s'" % (filename))
                        if CR_status == 0:
                            print "文件正在创建，属性标记为：creating"
                            logging.info(
                                "文件正在创建，属性标记为：creating %s %s %s" % (filename, item_event, datetime.datetime.now()))
                        else:
                            print "文件不存在，运行失败"
                            logging.info("文件不存在，运行失败 %s %s %s" % (filename, item_event, datetime.datetime.now()))

    # 可写文件被关闭(用于真正的创建)
    def process_IN_CLOSE_WRITE(self, event):
        method = "Create"
        filename = os.path.join(event.path, event.name)
        item_event = "创建完成"
        logging.info("%s %s %s" % (filename, item_event, datetime.datetime.now()))
        data = {'method': method, 'filename': filename,'item_event':item_event}
        exec_api(data)

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
    def process_IN_OVERFLOW(self, event):
        # 没有event.path
        # filename = os.path.join(event.path, event.name)
        item_event = "事件队列溢出"
        print item_event
        # print item_event, ":", filename

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


def exec_api(data):
    method = data['method']
    # print method,type(method)
    # 删除
    if method == "Delete":
        EXEC_status, EXEC_output = commands.getstatusoutput(
            "python /csc/inotify_delete.py '%s' '%s' '%s' '%s' " % (
                method, data['filename'], data['item_event'], ms_ip))

    # 创建
    elif method == "Create":
        EXEC_status, EXEC_output = commands.getstatusoutput(
                "python /csc/inotify_differentiatefile.py  '%s' '%s'" % (
                    data['filename'], data['item_event']))
    # 内容修改
    elif method == "Modify":
        EXEC_status, EXEC_output = commands.getstatusoutput(
                "python /csc/inotify_modify.py  '%s' '%s' '%s' " % (
                     data['filename'], data['item_event'], ms_ip))

    # 下载
    elif method == "Get":
        EXEC_status, EXEC_output = commands.getstatusoutput(
            "python /csc/inotify_download.py '%s' '%s' '%s' '%s' " % (
                method, data['filename'], data['item_event'], ms_ip))

    # 元数据修改--改名
    elif method == "Post":
        print "-------------"
        print method, data['filename'], data['item_event'], data['filename_new'], ms_ip
        EXEC_status, EXEC_output = commands.getstatusoutput(
            "python /csc/inotify_rename.py '%s' '%s' '%s' '%s' '%s' " % (
                method, data['filename'], data['item_event'], data['filename_new'], ms_ip))

    # 切分大文件
    # elif method == "Split":
    #     # print "1111"
    #     EXEC_status, EXEC_output = commands.getstatusoutput(
    #         "python /csc/inotify_splitfile.py '%s' '%s' " % (
    #             data['filename'], data['small_size']))
    #
    # # 选择oid上传
    # elif method == "Oid":
    #     # print "1111"
    #     EXEC_status, EXEC_output = commands.getstatusoutput(
    #         "python /csc/inotify_selectoid.py '%s' '%s' '%s' '%s'" % (
    #             method, data['filename'], data['item_event'], data['number']))
    # 区分
    # elif method == "QF":
    #     EXEC_status, EXEC_output = commands.getstatusoutput(
    #         "python /csc/inotify_differentiatefile.py '%s' '%s' '%s'" % (
    #                 method, data['filename'],data['item_event']))

    print EXEC_output

    # if EXEC_status == 0:
    #     # print "api操作成功"
    #     print EXEC_output
    #     if "Succeed!" in EXEC_output:
    #         return 1
    #     else:
    #         return 0
    # else:
    #     print "api操作失败"
    #     return 0


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
