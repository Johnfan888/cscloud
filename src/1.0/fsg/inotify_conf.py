#!/usr/bin/python
# coding:utf-8
# author: xjl
# 读取配置文件
import ConfigParser

cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')

ms_ip = cf.get('csc', 'ms_ip')
cs_ip = cf.get('csc', 'cs_ip')
obsid_limit_small = int(cf.get('csc', 'obsid_limit_small'))
obsid_limit_big = int(cf.get('csc', 'obsid_limit_big'))

pool = int(cf.get('csc', 'pool'))
small_size = int(cf.get('csc', 'smallfile_size'))
# obsid_limit_big = int(cf.get('csc', 'obsid_limit_big'))
split_sum = float(cf.get('csc', 'split_sum'))  # 针对切分文件，每台ds上最多容纳的切分文件数量
obsid_big_sum = int(cf.get('csc', 'obsid_big_sum'))  # 目前所有的obsid数量

FSMonitor_path = cf.get('path', 'FSMonitor')
size = int(cf.get('csc', 'size'))
