#!/usr/bin/python
# coding:utf-8
# author: xjl
# 指定oid
import sys
import logging
import datetime
import commands
import urllib
# 导入配置文件
import ConfigParser
import os
import multiprocessing

cf = ConfigParser.ConfigParser()
cf.read('/csc/csc.conf')
obs = cf.get('csc', 'obs')
obs = obs.split(',')
ms_ip = cf.get('csc', 'ms_ip')
print "=================指定oid===================="