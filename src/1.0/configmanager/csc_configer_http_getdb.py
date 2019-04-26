#!/usr/bin/python
# coding:utf-8
# author: xjl
# 接收浏览器的请求，并返回结果
import cgi
import commands
import os
os.environ['PYTHON_EGG_CACHE'] = '/srv/www/htdocs/configmanager/config'
'''
这个是因为python使用MySQLdb模块与mysql数据库交互时需要一个地方作为cache放置暂存的数据，
但是调用python解释器的用户（常常是服务器如apache的www用户）对于cache所指向的位置没有访问权限。
'''

print "Content-type: text/html\n\n"  # 必须要。HTTP头部的一部分，它会发送给浏览器告诉浏览器文件的内容类型。
form = cgi.FieldStorage()
'''获取表单中数据'''
secret = form['key1'].value  # 密钥
fun = form['key2'].value  # 方法
# print secret,method
if secret == "123123":
    # print "12333333333"
    import csc_getdb
    fun="csc_getdb."+fun
    output=eval(fun)
    # output=eval(output)
    # status, output = commands.getstatusoutput("python /srv/www/htdocs/manage/csc_getdb.py '%s'" %(fun))
    print output
    # print type(output)
    #
    # if form.has_key('key3'):
    #     inotify_getcsc_http+method+()

else:
    print "密钥出错"
