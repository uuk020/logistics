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
        $l = new Logistics();
        $this->expectException(NoQueryAvailableException::class);
        $this->expectExceptionMessage('sorry! no channel class available');
        $l->query('123213212', 'kuaidiBird');
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
            'status' => 0,
            'message'  => 'OK',
            'error_code' => 0,
            'data' => [
                ['time' => '1545444420', 'desc' => '仓库-已签收'],
                ['time' => '1545441977', 'desc' => '广东XX服务点'],
                ['time' => '1545438199', 'desc' => '广东XX转运中心']
            ],
            'logistics_company' => '申通快递',
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
            [
                'status' => 200,
                'message'  => 'OK',
                'error_code' => 0,
                'data' => [
                    ['time' => '1545444420', 'context' => '仓库-已签收'],
                    ['time' => '1545441977', 'context' => '广东XX服务点'],
                    ['time' => '1545438199', 'context' => '广东XX转运中心']
                ],
                'logistics_company' => '申通快递',
                'logistics_bill_no' => '12312211'
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
            'status' => 1,
            'message' => '',
            'error_code' => 0,
            'data' => [
                ['time' => '1545444420', 'context' => '仓库-已签收'],
                ['time' => '1545441977', 'context' => '广东XX服务点'],
                ['time' => '1545438199', 'context' => '广东XX转运中心']
            ],
            'logistics_company' => '优速快递',
            'logistics_bill_no' => '12312211',
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
            'baidu' =>[
                'exception' => '查询不到数据'
            ],
            'kuaidi100' => [
                'result' => [
                    [
                        'status' => 200,
                        'message'  => 'OK',
                        'error_code' => 0,
                        'data' => [
                            ['time' => '1545444420', 'desc' => '仓库-已签收'],
                            ['time' => '1545441977', 'desc' => '广东XX服务点'],
                            ['time' => '1545438199', 'desc' => '广东XX转运中心']
                        ],
                        'logistics_company' => '申通快递',
                        'logistics_bill_no' => '12312211'
                    ]
                ]
            ]
        ];
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('query')->andReturn($response);
        $this->assertSame($response, $logistics->query('12312211', ['baidu', 'kuaidi100', 'ickd']));
    }

}