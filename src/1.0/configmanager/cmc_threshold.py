#!/usr/bin/env python
# -*- encoding: utf8 -*-

#导入模块，urllib2是一个模拟浏览器HTTP方法的模块
import json
import urllib2
import sys
import MySQLdb
import commands
import time
import re
from urllib2 import Request,urlopen,URLError,HTTPError
sys.path.append('/usr/CMC/zabbix_api/')
import auth
from auth import MySQLport,MySQLuser,MySQLpasswd,MYSQLip,zabbix_url,zabbix_header,zabbix_user,zabbix_pass
class cmc_trigger:

    def __init__(self):
        #---------- 后续应该获取
        #self.manageip="192.168.1.225"
        # zabbix的API地址、用户名、密码、这里修改为实际的参数
        #self.zabbix_url = "http://"+self.manageip+"/zabbix/api_jsonrpc.php"
        #self.zabbix_header = {"Content-Type": "application/json"}
        #self.zabbix_user = "Admin"
        #self.zabbix_pass = "zabbix"
		        
        #-------------
        self.zabbix_url = zabbix_url
        self.zabbix_header = zabbix_header
        self.zabbix_user = zabbix_user
        self.zabbix_pass = zabbix_pass
        self.conn = MySQLdb.connect(
                host=MYSQLip,
				port=MySQLport,
				user=MySQLuser,
				passwd=MySQLpasswd,
				db='cmc',
				charset='utf8',
        )
    def user_login(self):
        # 下面是生成一个JSON格式的数据：用户名和密码
        auth_data = json.dumps(
            {
                "jsonrpc": "2.0",
                "method": "user.login",
                "params":
                    {
                        "user": self.zabbix_user,
                        "password": self.zabbix_pass
                    },
                "id": 0
            })

        # create request object
        request = urllib2.Request(self.zabbix_url, auth_data)
        for key in self.zabbix_header:
            request.add_header(key, self.zabbix_header[key])

        # 认证和获取SESSION ID
        try:
            result = urllib2.urlopen(request)
        # 对于认证出错的处理
        except HTTPError, e:
            print 'The server couldn\'t fulfill the request, Error code: ', e.code
        except URLError, e:
            print 'We failed to reach a server.Reason: ', e.reason
        else:
            response = json.loads(result.read())

        # 判断SESSIONID是否在返回的数据中
        if 'result' in response:
            self.auth_code = response['result']
        else:
            print response['error']['data']
        return self.auth_code
    def execute_api(self):
        create_data = json.dumps(self.json_data)
        request = urllib2.Request(self.zabbix_url, create_data)
        for key in self.zabbix_header:
            request.add_header(key, self.zabbix_header[key])
        # get  list
        try:
            result = urllib2.urlopen(request)
        except URLError as e:
            if hasattr(e, 'reason'):
                print 'We failed to reach a server.'
                print 'Reason: ', e.reason
            elif hasattr(e, 'code'):
                print 'The server could not fulfill the request.'
                print 'Error code: ', e.code
        else:

            self.response = json.loads(result.read())
            result.close()
            # print self.response
    def get_threshold(self):
        self.addnode_threshold = str((float(sys.argv[1])-0.05)*100)#接收阈值
        self.transfer_threshold = str(float(sys.argv[1])* 100)  # 接收阈值

        # print self.transfer_threshold
    def get_trigger(self):
        # 连接数据库
        cur = self.conn.cursor()
        # 清空数据库----------``进行区分

        sql = 'select `condition`,triggerId FROM main_ctrigger where triggerName="%s" '%(self.triggerName)
        # print sql
        cur.execute(sql)
        self.trigger_data=cur.fetchone()
        self.conn.commit()
        cur.close()

        self.trigger_expression =str(self.trigger_data[0])#表达式
        self.triggerId =  str(self.trigger_data[1])#triggerid
        # print self.trigger_expression

        self.pattern = re.compile(r">\d+\.\d+")#正则到原有阈值
        # print pattern.findall(self.trigger_expression)
        # self.trigger_expression = re.sub(self.pattern,">"+self.addnode_threshold , self.trigger_expression)#替换新阈值
        # print self.trigger_expression
    def update_trigger(self):

        self.json_data = {
                        "jsonrpc": "2.0",
                        "method": "trigger.update",
                        "params": {
                            "triggerid": self.triggerId,
                            # "description": triggersname,
                            "expression": self.trigger_expression,
                            # "comments": description,
                            # "priority": severity
                        },
                        "auth": self.user_login(),
                        "id": 1
                    }
        self.execute_api()
    def synchronization(self):
        # -----同步更新cmc中的trigger表-------
        cur = self.conn.cursor()
        sql = "update main_ctrigger set `condition`=%s where triggerId=%s"
        cur.execute(sql, [self.trigger_expression, self.triggerId])
        self.conn.commit()
        cur.close()
        # print "cmc monitoring is completed!"
    def addnode(self):
        self.triggerName="csc_addnode"
        self.get_trigger()
        self.trigger_expression = re.sub(self.pattern,">"+self.addnode_threshold , self.trigger_expression)#替换新阈值
        self.update_trigger()
        self.synchronization()
    def transfer(self):
        self.triggerName = "csc_transfer"
        self.get_trigger()
        self.trigger_expression = re.sub(self.pattern, ">" + self.transfer_threshold, self.trigger_expression)  # 替换新阈值
        self.update_trigger()
        self.synchronization()




if __name__ == "__main__":
    cmc=cmc_trigger()
    cmc.get_threshold()
    cmc.addnode()
    cmc.transfer()



