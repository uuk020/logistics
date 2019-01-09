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
$logistics->getLogisticsByName('12313131231'); // 第二参数不设,则默认快递100接口
$logistics->getLogisticsByName('12313131231', 'kuaidi100');
```
示例:

```php 
[  
   "status" => "200",
   "message" => "OK",
   "error_code" => "3",
   "data" => [
       ["time" => "2018-12-21 17:51:32", "ftime" => "2018-12-21 17:51:32", "context" => "已签收", "location" => NULL],
       ["time" => "2018-12-21 17:51:32", "ftime" => "2018-12-21 16:51:32", "context" => "派件中", "location" => NULL],
       ["time" => "2018-12-21 17:51:32", "ftime" => "2018-12-20 13:51:32" ,"context" => "已到达", "location" => NULL]
    ],
   "logistics_company" => "shentong",
   "logistics_bill_no" => "12313131231",
]
```

## 百度接口获取物流信息
```php
$logistics->getLogisticsByName('12313131231', 'baidu');
```
示例:

```php 
[  
   "status" => "0",
   "message" => "",
   "error_code" => "0",
   "data" => [
       ["time" => "1545444420", "desc" => "已签收"],
       ["time" => "1545441977", "desc" => "派件中"],
       ["time" => "1545438199", "desc" => "已到达"]
    ],
   "logistics_company" => "shentong",
   "logistics_bill_no" => "12313131231",
]
```

## 多接口获取物流信息
```php
$logistics->getLogisticsByArray('12313131231'); // 只要一个接口请求成功, 停止请求下一个接口
$logistics->getLogisticsByArray('12313131231', ['baidu', 'kuaidi100']);
```
示例:

```php 
[
   "kuaidi100" => [
       "info" => [  
           status" => "200",
           "message" => "OK",
           "error_code" => "3",
           "data" => [
                ["time" => "2018-12-21 17:51:32", "ftime" => "2018-12-21 17:51:32", "context" => "已签收", "location" => NULL],
                ["time" => "2018-12-21 17:51:32", "ftime" => "2018-12-21 16:51:32", "context" => "派件中", "location" => NULL],
                ["time" => "2018-12-21 17:51:32", "ftime" => "2018-12-20 13:51:32" ,"context" => "已到达", "location" => NULL]
            ],
           "logistics_company" => "shentong",
           "logistics_bill_no" => "12313131231",
        ]
   ]
]
```


## 参数说明
```
array getLogisticsByName(string $code, $queryName = 'kuaidi100')

array getLogisticsByArray(string $code, $queryArray = ['kuaidi100', 'baidu'])
```

* $code - 运单号
* $queryName - 接口名称, 目前支持百度(baidu), 快递100(kuaidi100)
* $queryArray - 接口数组, 如: ['kuaidi100', 'baidu']

## 参考
* [高德开放平台接口的 PHP 天气信息组件(weather)](https://github.com/overtrue/weather)
* [满足你的多种发送需求的短信发送组件(easy-sms)](https://github.com/overtrue/easy-sms)

## 最后
欢迎提出issue


## License

MIT
### Code Visualization:

Here is a cool visualization of the code evolution

 [![Watch the video](https://img.youtube.com/vi/oi9-eGq_jDQ/0.jpg)](https://www.youtube.com/watch?v=oi9-eGq_jDQ)

 [https://www.youtube.com/watch?v=oi9-eGq_jDQ](https://www.youtube.com/watch?v=oi9-eGq_jDQ)

