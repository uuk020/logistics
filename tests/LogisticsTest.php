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

    public function testGetLogisticsForBaidu()
    {
        $response = [
            'status' => 0,
            'message'  => '',
            'error_code' => 0,
            'data' => [
                ['time' => '1545444420', 'desc' => '仓库-已签收'],
                ['time' => '1545441977', 'desc' => '广东XX服务点'],
                ['time' => '1545438199', 'desc' => '广东XX转运中心']
            ],
            'logistics_company' => '申通快递',
        ];
        $factory = \Mockery::mock(Factory::class);
        $baidu = \Mockery::mock(BaiduQuery::class);
        $baidu->shouldReceive('callInterface')->andReturn($response);
        $factory->shouldReceive('getInstance')->andReturn($baidu);
        $logistics = new Logistics($factory);
        $this->assertSame($response, $logistics->getLogistics('12312211'));
    }

    public function testGetLogisticsForKuaidi100()
    {
        $response = [
            [],
            [],
        ];
        $factory = \Mockery::mock(Factory::class);
        $kuaidi = \Mockery::mock(Kuaidi100Query::class);
        
    }
}