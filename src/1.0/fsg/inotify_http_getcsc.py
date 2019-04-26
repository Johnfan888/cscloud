#!/usr/bin/python
# coding:utf-8
# author: xjl
#访问csc的数据库api
import requests
import sys
from inotify_conf import ms_ip,cs_ip
reload(sys)
sys.setdefaultencoding( "utf-8" )
# python在安装时，默认的编码是ascii，当程序中出现非ascii编码时，python的处理常常会报这样的错UnicodeDecodeError: 'ascii' codec can't decode byte 0x?? in position 1: ordinal not in range(128)，python没办法处理非ascii编码的，此时需要自己设置将python的默认编码，一般设置为utf8的编码格式。

def main(server,method):
    secret = 123123
    payload = {'key1': secret, 'key2': method}
    if server == "1":
        r = requests.get('http://%s/manage/csc_manage_http_getdb.py'%(ms_ip),params=payload)
    else:
        print "2"
        r = requests.get('http://%s/configmanager/csc_configer_http_getdb.py'%(cs_ip), params=payload)

    #r = requests.post('http://192.168.1.224/manage/xjl_test/receive1.py',data=payload,timout=0.1)
    # r = requests.get('http://192.168.1.224/manage/csc_manage_http_getdb.py',params=payload)
    # r = requests.get('http://192.168.1.225/configmanager/csc_configer_http_getdb.py', params=payload)
    # print r.url
    # print r.text,type(r.text)#unicode
    output = r.content.strip()#字符串，去空行
    # output=output.split(' ')
    # print output,type(output)
    # output=str(r.text ).strip()
    # print output
    # print output
    return output
if __name__ == '__main__':
    server = sys.argv[1]
    method = sys.argv[2]
    # print type(method)
    aa=main(server,method)
    print aa
    # print output[1]#打印解码后的返回数据,输出unicode
    # print r.status_code
    # print type(r.text)
    # aa=str(r.text)
    # print aa,type(aa)
