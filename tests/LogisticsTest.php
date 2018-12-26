<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2018/12/25
 * Time: 23:07
 */

namespace Wythe\Logistics\Tests;

use PHPUnit\Framework\TestCase;
use Wythe\Logistics\Exceptions\InvalidArgumentException;
use Wythe\Logistics\Logistics;

class LogisticsTest extends TestCase
{
    public function testGetSendingTimeWithInvalidParams()
    {
        $l = new Logistics();
        // 断言会抛出此异常类
        $this->expectException(InvalidArgumentException::class);
        // 断言异常消息为 $code和$type参数不能为空
        $this->expectExceptionMessage('$code和$type参数不能为空');
        $l->getSendingTime('312313121', '');
        $this->fail('Failed to assert getLogistics throw exception with invalid argument.');
    }

    public function testGetLogisticsWithInvalidParams()
    {
        $l = new Logistics();
        // 断言会抛出此异常类
        $this->expectException(InvalidArgumentException::class);
        // 断言异常消息为 $code和$type参数不能为空
        $this->expectExceptionMessage('$code和$type参数不能为空');
        $l->getLogistics('312313121', '');
        $this->fail('Failed to assert getLogistics throw exception with invalid argument.');
    }

    public function testGetLogistics()
    {

    }
}