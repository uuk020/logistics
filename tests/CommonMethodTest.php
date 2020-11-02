<?php

/*
 * This file is part of the uuk020/logistics.
 *
 * (c) WytheHuang<wythe.huangw@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wythe\Logistics\Tests;

use PHPUnit\Framework\TestCase;
use Wythe\Logistics\Channel\JiSuChannel;
use Wythe\Logistics\Factory;
use Wythe\Logistics\SupportLogistics;

class CommonMethodTest extends TestCase
{
    /**
     * 测试设置默认渠道接口.
     *
     * @throws \Wythe\Logistics\Exceptions\Exception
     */
    public function testSetFactoryDefault()
    {
        $factory = new Factory();
        $factory->setDefault('kuaiDi100');
        $this->assertSame('kuaiDi100', $factory->getDefault());
    }

    /**
     * 测试获取快递公司编码
     *
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    public function testSupportLogistics()
    {
        $supportLogistics = \Mockery::mock(SupportLogistics::class);
        $supportLogistics->shouldReceive('getCode')->andReturn('shunfeng');
        $this->assertSame('shunfeng', $supportLogistics->getCode('kuaiDi100', '12331231', '顺丰'));
    }
}
