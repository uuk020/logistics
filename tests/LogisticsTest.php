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
use Wythe\Logistics\Exceptions\InvalidArgumentException;
use Wythe\Logistics\Logistics;

class LogisticsTest extends TestCase
{
    /**
     * 测试不传参数.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelWithInvalidParams()
    {
        $l = new Logistics();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('code arguments cannot empty.');
        $l->query('', '');
    }

    /**
     * 测试传不存在渠道.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelWithChannelClass()
    {
        $response = [
            'kuaidBird' => [
                'channel' => 'kuaidiBird',
                'status' => 'failure',
                'exception' => 'Wythe\Logistics\Channel\kuaidBirdChannel" not exists.',
            ],
        ];
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'kuaidBird'));
    }

    /**
     * 测试快递100渠道.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByKuaidi100()
    {
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
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($kuaidiResponse);
        $this->assertSame($kuaidiResponse, $logistics->query('12312211', 'kuaidi100', '申通'));
    }

    /**
     * 测试爱查快递渠道.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByIckd()
    {
        $response = [
            'ickd' => [
                'channel' => 'ickd',
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
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'ickd'));
    }

    /**
     * 测试极速数据.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByJiSu()
    {
        $response = [
            'jiSu' => [
                'channel' => 'jiSu',
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
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'jiSu'));
    }

    /**
     * 测试聚合数据.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByJuHe()
    {
        $response = [
            'juHe' => [
                'channel' => 'juHe',
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
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'juHe', '申通'));
    }

    /**
     * 测试数据智汇.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByShuJu()
    {
        $response = [
            'shuJuZhiHui' => [
                'channel' => 'shuJuZhiHui',
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
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'shuJuZhiHui', '申通'));
    }

    /**
     * 测试快递鸟
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByKuaiDiBird()
    {
        $response = [
            'kuaiDiBird' => [
                'channel' => 'kuaiDiBird',
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
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'juHe', '申通'));
    }

    /**
     * 测试全部渠道.
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testChannelByBoth()
    {
        $response = [
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
            'kuaiDiBird' => [
                'channel' => 'kuaiDiBird',
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
            'juHe' => [
                'channel' => 'juHe',
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
            'jiSu' => [
                'channel' => 'jiSu',
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
            'shuJuZhiHui' => [
                'channel' => 'shuJuZhiHui',
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
            'ickd' => [
                'channel' => 'ickd',
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
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', ['kuaiDi100', 'kuaiDiBird', 'juHe', 'jiShu',
            'shuJuZhiHui', 'ickd', ], '申通'));
    }
}
