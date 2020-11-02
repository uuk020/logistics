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
use Wythe\Logistics\Exceptions\ConfigNotFoundException;
use Wythe\Logistics\Exceptions\ConfigValidateException;
use Wythe\Logistics\Exceptions\InvalidArgumentException;
use Wythe\Logistics\Logistics;

class LogisticsTest extends TestCase
{

    public function testNotExistConfig()
    {
        $config = [
            'ickd' => ['app_key' => 'app_key', 'vip' => false],
        ];
        $this->expectException(ConfigNotFoundException::class);
        $this->expectExceptionMessage('没找到相对应配置规则');
        $l = new Logistics($config);
    }

    public function testMistakeConfig()
    {
        $config = [
            'juhe' => ['app_key1' => 'app_key', 'vip' => false]
        ];
        $this->expectException(ConfigValidateException::class);
        $this->expectExceptionMessage('规则验证失败');
        $l = new Logistics($config);
        $l->query('12312312', 'juhe');
    }

    /**
     * 测试不传参数.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelWithInvalidParams()
    {
        $config = [
            'juhe' => ['app_key' => 'app_key', 'vip' => false],
        ];
        $l = new Logistics($config);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('code arguments cannot empty.');
        $l->query('', 'juhe');
    }

    /**
     * 测试快递100渠道.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByKuaidi100()
    {
        $config = [
            'kuaidi100' => ['app_key' => 'app_key', 'app_secret' => 'app_secret'],
        ];
        $kuaidiResponse = [
            'kuaidi100' => [
                'channel' => 'kuaiDi100',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message' => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '2019-06-10 00:00:00', 'description' => '仓库-已签收'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX服务点'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX转运中心'],
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211',
                    ],
                ],
            ],
        ];
        $logistics = \Mockery::mock(Logistics::class, [$config]);
        $logistics->shouldReceive('query')->andReturn($kuaidiResponse);
        $this->assertSame($kuaidiResponse, $logistics->query('12312211', 'kuaidi100', '申通'));
    }

    /**
     * 测试极速数据.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByJiSu()
    {
        $config = [
            'jisu' => ['app_key' => 'app_key', 'vip' => false],
        ];
        $response = [
            'jisu' => [
                'channel' => 'jisu',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message' => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '2019-06-10 00:00:00', 'description' => '仓库-已签收'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX服务点'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX转运中心'],
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211',
                    ],
                ],
            ],
        ];
        $logistics = \Mockery::mock(Logistics::class, [$config]);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'jisu'));
    }

    /**
     * 测试聚合数据.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByJuHe()
    {
        $config = [
            'juhe' => ['app_key' => 'app_key', 'vip' => false],
        ];
        $response = [
            'juhe' => [
                'channel' => 'juhe',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message' => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '2019-06-10 00:00:00', 'description' => '仓库-已签收'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX服务点'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX转运中心'],
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211',
                    ],
                ],
            ],
        ];
        $logistics = \Mockery::mock(Logistics::class, [$config]);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'juhe', '申通'));
    }

    /**
     * 测试数据智汇.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByShuJu()
    {
        $config = [
            'shujuzhihui' => ['app_key' => 'app_key', 'vip' => false]
        ];
        $response = [
            'shujuzhihui' => [
                'channel' => 'shujuzhihui',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message' => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '2019-06-10 00:00:00', 'description' => '仓库-已签收'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX服务点'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX转运中心'],
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211',
                    ],
                ],
            ],
        ];
        $logistics = \Mockery::mock(Logistics::class, [$config]);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'shujuzhihui', '申通'));
    }

    /**
     * 测试快递鸟
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByKuaiDiBird()
    {
        $config = [
            'kuaidibird' => ['app_key' => 'app_key', 'app_secret' => 'app_secret', 'vip' => false],
        ];
        $response = [
            'kuaidibird' => [
                'channel' => 'kuaidibird',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message' => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '2019-06-10 00:00:00', 'description' => '仓库-已签收'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX服务点'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX转运中心'],
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211',
                    ],
                ],
            ],
        ];
        $logistics = \Mockery::mock(Logistics::class, [$config]);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'kuaidibird', '申通'));
    }

    /**
     * 测试全部渠道.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByBoth()
    {
        $config = [
            'kuaidi100' => ['app_key' => 'app_key', 'app_secret' => 'app_secret'],
            'kuaidibird' => ['app_key' => 'app_key', 'app_secret' => 'app_secret', 'vip' => false],
            'juhe' => ['app_key' => 'app_key', 'vip' => false],
            'jisu' => ['app_key' => 'app_key', 'vip' => false],
            'shujuzhihui' => ['app_key' => 'app_key', 'vip' => false]
        ];
        $response = [
            'kuaidi100' => [
                'channel' => 'kuaidi100',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message' => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '2019-06-10 00:00:00', 'description' => '仓库-已签收'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX服务点'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX转运中心'],
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211',
                    ],
                ],
            ],
            'kuaidibird' => [
                'channel' => 'kuaidibird',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message' => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '2019-06-10 00:00:00', 'description' => '仓库-已签收'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX服务点'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX转运中心'],
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211',
                    ],
                ],
            ],
            'juhe' => [
                'channel' => 'juhe',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message' => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '2019-06-10 00:00:00', 'description' => '仓库-已签收'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX服务点'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX转运中心'],
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211',
                    ],
                ],
            ],
            'jisu' => [
                'channel' => 'jisu',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message' => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '2019-06-10 00:00:00', 'description' => '仓库-已签收'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX服务点'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX转运中心'],
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211',
                    ],
                ],
            ],
            'shujuzhihui' => [
                'channel' => 'shujuzhihui',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message' => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '2019-06-10 00:00:00', 'description' => '仓库-已签收'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX服务点'],
                            ['time' => '2019-06-10 00:00:00', 'description' => '广东XX转运中心'],
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211',
                    ],
                ],
            ],
        ];
        $logistics = \Mockery::mock(Logistics::class, [$config]);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', array_keys($config),'申通'));
    }
}
