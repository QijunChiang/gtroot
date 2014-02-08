#!/usr/bin python
#coding=utf8
__author__ = 'QijunChiang'

import sys
import httplib

def _do_url_request(host, url="/", port=80, timeout=20):
    httpClient = None
    try:
        httpClient = httplib.HTTPConnection(host, port, timeout)
        httpClient.request('GET', url)
        #response是HTTPResponse对象
        #不等待处理结果。
        #response = httpClient.getresponse()
        #if response.status == 200:
        #    print(response.read())
    except Exception as e:
        print(e)
    finally:
        if httpClient:
            httpClient.close()
if __name__ == "__main__":
    length = len(sys.argv)   #参数个数
    if length == 2:
        _do_url_request(sys.argv[1])
    elif length == 3:
        _do_url_request(sys.argv[1], sys.argv[2])
    elif length == 4:
        _do_url_request(sys.argv[1], sys.argv[2], sys.argv[3])
    elif length == 5:
        _do_url_request(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4])
    else:
        print False