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
use Wythe\Logistics\Query\Kuaidi100Query;

class LogisticsTest extends TestCase
{
    public function testSetFactoryDefault()
    {
        $factory = new Factory();
        $factory->setDefault('kuaidi');
        $this->assertSame('kuaidi', $factory->getDefault());
    }

    public function testGetLogisticsWithInvalidParams()
    {
        $l = new Logistics();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('code arguments cannot empty.');
        $l->getLogisticsByName('', '');
    }

    public function testGetLogisticsWithQueryClass()
    {
        $l = new Logistics();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class "Wythe\Logistics\Query\KuaidiBirdQuery" not exists.');
        $l->getLogisticsByName('123213212', 'kuaidiBird');
    }

    public function testGetLogisticsByBaidu()
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
        $logistics->shouldReceive('getLogisticsByName')->andReturn($response);
        $this->assertSame($response, $logistics->getLogisticsByName('12312211', 'baidu'));
    }

    public function testGetLogisticsByKuaidi100()
    {
        $kuaidiResponse = [
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
            ],
        ];
        $logistics = \Mockery::mock(Logistics::class);
        $logistics->shouldReceive('getLogisticsByName')->andReturn($kuaidiResponse);
        $this->assertSame($kuaidiResponse, $logistics->getLogisticsByName('12312211', 'kuaidi100'));
    }

    public function testGetLogisticsBoth()
    {
        $response = [
            'baidu' =>[
                'exception' => '查询不到数据'
            ],
            'kuaidi100' => [
                'info' => [
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
        $logistics->shouldReceive('getLogisticsByArray')->andReturn($response);
        $this->assertSame($response, $logistics->getLogisticsByArray('12312211', ['baidu', 'kuaidi100']));
    }

}