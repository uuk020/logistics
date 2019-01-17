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

class LogisticsTest extends TestCase
{
    /**
     * 测试设置默认渠道接口
     *
     * @throws \Wythe\Logistics\Exceptions\Exception
     */
    public function testSetFactoryDefault()
    {
        $factory = new Factory();
        $factory->setDefault('kuaidi');
        $this->assertSame('kuaidi', $factory->getDefault());
    }

    /**
     * 测试不传参数
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testQueryWithInvalidParams()
    {
        $l = new Logistics();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('code arguments cannot empty.');
        $l->query('', '');
    }

    /**
     * 测试传不存在渠道
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testQueryWithQueryClass()
    {
        $response = [
            'kuaidBird' => [
                'channel' => 'kuaidiBird',
                'status' => 'failure',
                'exception' => 'Wythe\Logistics\Channel\kuaidBirdChannel" not exists.'
            ]
        ];
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'kuaidBird'));
    }

    /**
     * 测试百度渠道
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testQueryByBaidu()
    {
        $response = [
            'baidu' => [
                'channel' => 'baidu',
                'status' => 'success',
                'result' => [
                    'status' => 0,
                    'message'  => 'OK',
                    'error_code' => 0,
                    'data' => [
                        ['time' => '2019-01-09 12:11', 'desc' => '仓库-已签收'],
                        ['time' => '2019-01-09 12:11', 'desc' => '广东XX服务点'],
                        ['time' => '2019-01-09 12:11', 'desc' => '广东XX转运中心']
                    ],
                    'logistics_company' => '申通快递',
                ]
            ],
        ];
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'baidu'));
    }

    /**
     * 测试快递100渠道
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testQueryByKuaidi100()
    {
        $kuaidiResponse = [
            'kuaidi100' => [
                'channel' => 'kuaidi100',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message'  => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '1545444420', 'description' => '仓库-已签收'],
                            ['time' => '1545441977', 'description' => '广东XX服务点'],
                            ['time' => '1545438199', 'description' => '广东XX转运中心']
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
        ];
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($kuaidiResponse);
        $this->assertSame($kuaidiResponse, $logistics->query('12312211', 'kuaidi100'));
    }

    /**
     * 测试爱查快递渠道
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testQueryByIckd()
    {
        $response = [
            'ickd' => [
                'channel' => 'ickd',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message'  => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '1545444420', 'description' => '仓库-已签收'],
                            ['time' => '1545441977', 'description' => '广东XX服务点'],
                            ['time' => '1545438199', 'description' => '广东XX转运中心']
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211'
                    ]
               ]
            ]
        ];
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', 'ickd'));
    }


    /**
     * 测试全部渠道
     *
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function testQueryByBoth()
    {
        $response = [
            'baidu' => [
                'channel' => 'baidu',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message'  => '抱歉，查询出错，请重试或点击快递公司官网地址进行查询',
                        'error_code' => 0,
                        'data' => '',
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211'
                    ]
                ]
            ],
            'kuaidi100' => [
                'channel' => 'kuaidi100',
                'status' => 'success',
                'result' => [
                    [
                        'status' => 200,
                        'message'  => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '1545444420', 'description' => '仓库-已签收'],
                            ['time' => '1545441977', 'description' => '广东XX服务点'],
                            ['time' => '1545438199', 'description' => '广东XX转运中心']
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
                            ['time' => '1545444420', 'description' => '仓库-已签收'],
                            ['time' => '1545441977', 'description' => '广东XX服务点'],
                            ['time' => '1545438199', 'description' => '广东XX转运中心']
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211'
                    ]
                ]
            ],
        ];
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', ['baidu', 'kuaidi100', 'ickd']));
    }



}