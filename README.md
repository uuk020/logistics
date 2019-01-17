<h1 align="center"> Logistics </h1>

<p align="center">无需授权和配置, 简单便捷查询运单快递信息</p>

[![Build Status](https://travis-ci.org/uuk020/logistics.svg?branch=master)](https://travis-ci.org/uuk020/logistics)

## 环境需求
* PHP >= 7.0

## 安装

```shell
$ composer require wythe/logistics -vvv
```

## 使用
```php
use Wythe\Logistics\Logistics
$logistics = new Logisitics()
```

## 快递100接口获取物流信息
```php
$logistics->query('12313131231'); // 第二参数不设,则默认快递100接口
$logistics->query('12313131231', 'kuaidi100');
$logistics->query('12313131231', ['kuaidi100']);
```
示例:

```php 
[
   'kuaidi100' => [
       'channel' => 'kuaidi100',
       'status' => 'success',
       'result' => [
           [
               'status' => 200,
               'message'  => 'OK',
               'error_code' => 0,
               'data' => [
                   ['time' => '2019-01-09 12:11', 'context' => '仓库-已签收'],
                   ['time' => '2019-01-07 12:11', 'context' => '广东XX服务点'],
                   ['time' => '2019-01-06 12:11', 'context' => '广东XX转运中心']
               ],
               'logistics_company' => '申通快递',
               'logistics_bill_no' => '12312211'
           ],
           [
               'status' => 201,
               'message' => '快递公司参数异常：单号不存在或者已经过期',
               'error_code' => 0,
               'data' => '',
               'logistics_company' => '',
               'logistics_bill_no' => ''
           ]
       ]
   ]
]
```

## 爱查快递接口获取物流信息
```php
$logistics->query('12313131231', 'ickd');
$logistics->query('12313131231', ['ickd']);
```
示例:

```php 
[
   'ickd' => [
       'channel' => 'ickd',
       'status' => 'success',
       'result' => [
           [
               'status' => 200,
               'message'  => 'OK',
               'error_code' => 0,
               'data' => [
                   ['time' => '2019-01-09 12:11', 'context' => '仓库-已签收'],
                   ['time' => '2019-01-07 12:11', 'context' => '广东XX服务点'],
                   ['time' => '2019-01-06 12:11', 'context' => '广东XX转运中心']
               ],
               'logistics_company' => '申通快递',
               'logistics_bill_no' => '12312211'
           ]
       ]
   ]
]
```

## 百度接口获取物流信息
```php
$logistics->query('12313131231', 'baidu');
$logistics->query('12313131231', ['baidu']);
```
示例:

```php 
[
   'baidu' => [
       'channel' => 'baidu',
       'status' => 'success',
       'result' => [
           [
               'status' => 200,
               'message'  => 'OK',
               'error_code' => 0,
               'data' => [
                   ['time' => '2019-01-09 12:11', 'desc' => '仓库-已签收'],
                   ['time' => '2019-01-09 12:11', 'desc' => '广东XX服务点'],
                   ['time' => '2019-01-09 12:11', 'desc' => '广东XX转运中心']
               ],
               'logistics_company' => '申通快递',
               'logistics_bill_no' => '12312211'
           ]
       ]
   ]
]
```

## 多接口获取物流信息
```php
$logistics->query('12313131231');
$logistics->query('12313131231', ['kuaidi100', 'ickd', 'baidu']);
```
示例:

```php 
[
   'kuaidi100' => [
       'channel' => 'kuaidi100',
       'status' => 'success',
       'result' => [
           [
               'status' => 200,
               'message'  => 'OK',
               'error_code' => 0,
               'data' => [
                   ['time' => '2019-01-09 12:11', 'context' => '仓库-已签收'],
                   ['time' => '2019-01-07 12:11', 'context' => '广东XX服务点'],
                   ['time' => '2019-01-06 12:11', 'context' => '广东XX转运中心']
               ],
               'logistics_company' => '申通快递',
               'logistics_bill_no' => '12312211'
           ],
           [
               'status' => 201,
               'message' => '快递公司参数异常：单号不存在或者已经过期',
               'error_code' => 0,
               'data' => '',
               'logistics_company' => '',
               'logistics_bill_no' => ''
           ]
       ]
   ],
   'ickd' => [
       'channel' => 'ickd',
       'status' => 'success',
       'result' => [
           [
               'status' => 200,
               'message'  => 'OK',
               'error_code' => 0,
                'data' => [
                    ['time' => '2019-01-09 12:11', 'context' => '仓库-已签收'],
                    ['time' => '2019-01-07 12:11', 'context' => '广东XX服务点'],
                    ['time' => '2019-01-06 12:11', 'context' => '广东XX转运中心']
                ],
                'logistics_company' => '申通快递',
                'logistics_bill_no' => '12312211'
           ]
       ]
   ],
   'baidu' => [
       'channel' => 'baidu',
       'status' => 'success',
       'result' => [
           [
               'status' => 200,
               'message'  => 'OK',
               'error_code' => 0,
               'data' => [
                   ['time' => '2019-01-09 12:11', 'desc' => '仓库-已签收'],
                   ['time' => '2019-01-09 12:11', 'desc' => '广东XX服务点'],
                   ['time' => '2019-01-09 12:11', 'desc' => '广东XX转运中心']
               ],
               'logistics_company' => '申通快递',
               'logistics_bill_no' => '12312211'
           ]
       ]
   ]
]
```

## 参数说明
```
array query(string $code, $channels = ['kuaidi100'])
```

* $code - 运单号
* $channel - 渠道名称, 可选参数,默认快递100.目前支持百度(baidu), 快递100(kuaidi100), 爱查快递(ickd)

## 有关请求次数
据测试随机生成100个运单循环请求, 目前没有限制. 但这些接口都是我抓包得来, 虽然我随机设置user-agent,
但是不够保险, 因此可能请求过多会有限制,就要更换IP来去请求.

## 参考
* [PHP 扩展包实战教程 - 从入门到发布](https://laravel-china.org/courses/creating-package)
* [高德开放平台接口的 PHP 天气信息组件(weather)](https://github.com/overtrue/weather)
* [满足你的多种发送需求的短信发送组件(easy-sms)](https://github.com/overtrue/easy-sms)

## 最后
感谢安正超 - 超哥提供教程, 让我知道如何构建一个包, 学习到很多东西. 
其实我才做PHP没多久, 没想到有这么多人star. 
虽然现在才121,但对我来说是一份认可, 谢谢.

欢迎提出issue和pull request


## License

MIT
