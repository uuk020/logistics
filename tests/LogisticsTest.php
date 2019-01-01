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
use Wythe\Logistics\Exceptions\NoQueryAvailableException;
use Wythe\Logistics\Factory;
use Wythe\Logistics\Logistics;
use Wythe\Logistics\Query\BaiduQuery;

class LogisticsTest extends TestCase
{
    public function testGetLogisticsWithInvalidParams()
    {
        $f = new Factory();
        $l = new Logistics($f);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('code arguments cannot empty.');
        $l->getLogistics('', '');
        $this->expectException(NoQueryAvailableException::class);
        $this->expectExceptionMessage('sorry! no query class available');
        $l->getLogistics('1231231231', '', ['KuaidiBird']);
        $this->fail('Failed to assert getLogistics throw exception with invalid argument.');
    }

    public function testGetLogistics()
    {
    }
}