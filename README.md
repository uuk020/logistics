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
$logistics = new Logistics()
```

## 快递 100 接口获取物流信息
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
                   ['time' => '2019-01-09 12:11', 'description' => '仓库-已签收'],
                   ['time' => '2019-01-07 12:11', 'description' => '广东XX服务点'],
                   ['time' => '2019-01-06 12:11', 'description' => '广东XX转运中心']
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
                   ['time' => '2019-01-09 12:11', 'description' => '仓库-已签收'],
                   ['time' => '2019-01-07 12:11', 'description' => '广东XX服务点'],
                   ['time' => '2019-01-06 12:11', 'description' => '广东XX转运中心']
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
$logistics->query('12313131231', ['kuaidi100', 'ickd']);
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
                   ['time' => '2019-01-09 12:11', 'description' => '仓库-已签收'],
                   ['time' => '2019-01-07 12:11', 'description' => '广东XX服务点'],
                   ['time' => '2019-01-06 12:11', 'description' => '广东XX转运中心']
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
                    ['time' => '2019-01-09 12:11', 'description' => '仓库-已签收'],
                    ['time' => '2019-01-07 12:11', 'description' => '广东XX服务点'],
                    ['time' => '2019-01-06 12:11', 'description' => '广东XX转运中心']
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
array queryByProxy(array $proxy, string $code, $channels = ['kuaidi100'])
```

* query 与 queryByProxy 返回数组结构是一样, 只是多了一个参数代理IP
* $proxy - 代理地址 结构: ['proxy' => '代理IP:代理端口']
* $code - 运单号
* $channel - 渠道名称, 可选参数,默认快递 100.目前支持百度(baidu), 快递 100 (kuaidi 100), 爱查快递(ickd)

## 有关请求次数
请求次数过于频繁, Kudidi100会封IP, 而ickd则不会, 但会返回错误信息, 假如需要请求多次, 则需要代理IP. 我试着抓免费代理IP
基本上没一个可以用, 即使有用, 但存活时间很短. 因此只增加代理IP参数.

## 参考
* [PHP 扩展包实战教程 - 从入门到发布](https://laravel-china.org/courses/creating-package)
* [高德开放平台接口的 PHP 天气信息组件(weather)](https://github.com/overtrue/weather)
* [满足你的多种发送需求的短信发送组件(easy-sms)](https://github.com/overtrue/easy-sms)

## 最后
欢迎提出 issue 和 pull request

## License

MIT
