<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/6/23
 * Time: 0:12.
 */
declare(strict_types=1);

/*
 * This file is part of the uuk020/logistics.
 *
 * (c) WytheHuang<wythe.huangw@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wythe\Logistics;

use Wythe\Logistics\Traits\HttpRequest;

class SupportLogistics
{
    use HttpRequest;

    private $companyList = [
        '顺丰' => ['juhe' => 'sf', 'kuaidi100' => 'shunfeng', 'kuaidibird' => 'SF'],
        '申通' => ['juhe' => 'sto', 'kuaidi100' => 'shentong', 'kuaidibird' => 'STO'],
        '中通' => ['juhe' => 'zto', 'kuaidi100' => 'zhongtong', 'kuaidibird' => 'ZTO'],
        '圆通' => ['juhe' => 'yt', 'kuaidi100' => 'yuantong', 'kuaidibird' => 'YTO'],
        '韵达' => ['juhe' => 'yd', 'kuaidi100' => 'yunda', 'kuaidibird' => 'YD'],
        '天天' => ['juhe' => 'tt', 'kuaidi100' => 'tiantian', 'kuaidibird' => 'HHTT'],
        'ems' => ['juhe' => 'ems', 'kuaidi100' => 'ems', 'kuaidibird' => 'EMS'],
        'ems国际' => ['juhe' => 'emsg', 'kuaidi100' => 'emsguoji', 'kuaidibird' => 'EMSGJ'],
        '汇通' => ['juhe' => 'ht', 'kuaidi100' => 'huitongkuaidi', 'kuaidibird' => ''],
        '全峰' => ['juhe' => 'qf', 'kuaidi100' => 'quanfengkuaidi', 'kuaidibird' => ''],
        '德邦' => ['juhe' => 'db', 'kuaidi100' => 'debangwuliu', 'kuaidibird' => 'DBL'],
        '国通' => ['juhe' => 'gt', 'kuaidi100' => 'guotongkuaidi', 'kuaidibird' => ''],
        '京东' => ['juhe' => 'jd', 'kuaidi100' => 'jd', 'kuaidibird' => 'JD'],
        '宅急送' => ['juhe' => 'zjs', 'kuaidi100' => 'zhaijisong', 'kuaidibird' => 'ZJS'],
        'fedex' => ['juhe' => 'fedex', 'kuaidi100' => 'fedex', 'kuaidibird' => 'FEDEX_GJ'],
        'ups' => ['juhe' => 'ups', 'kuaidi100' => '', 'kuaidibird' => 'UPS'],
        '中铁' => ['juhe' => 'ztky', 'kuaidi100' => '', 'kuaidibird' => 'ZHWL'],
        '佳吉' => ['juhe' => 'jiaji', 'kuaidi100' => 'jiajiwuliu', 'kuaidibird' => 'CNEX'],
        '速尔' => ['juhe' => 'suer', 'kuaidi100' => 'suer', 'kuaidibird' => 'SURE'],
        '信丰' => ['juhe' => 'xfwl', 'kuaidi100' => 'xinfengwuliu', 'kuaidibird' => 'XFEX'],
        '优速' => ['juhe' => 'yousu', 'kuaidi100' => 'youshuwuliu', 'kuaidibird' => 'UC'],
        '中邮' => ['juhe' => 'zhongyou', 'kuaidi100' => 'zhongyouwuliu', 'kuaidibird' => 'ZYKD'],
        '天地华宇' => ['juhe' => 'tdhy', 'kuaidi100' => 'tiandihuayu', 'kuaidibird' => 'HOAU'],
        '安信达' => ['juhe' => 'axd', 'kuaidi100' => 'anxindakuaixi', 'kuaidibird' => ''],
        '快捷' => ['juhe' => 'kuaijie', 'kuaidi100' => 'kuaijiesudi', 'kuaidibird' => 'DJKJWL'],
        'aae' => ['juhe' => 'aae', 'kuaidi100' => 'aae', 'kuaidibird' => 'AAE'],
        'dhl国内件' => ['juhe' => 'dhl', 'kuaidi100' => 'dhl', 'kuaidibird' => 'FEDEX'],
        'dhl国际件' => ['juhe' => 'dhl', 'kuaidi100' => 'dhlen', 'kuaidibird' => 'FEDEX_GJ'],
        'dpex国际' => ['juhe' => 'dpex', 'kuaidi100' => 'dpex', 'kuaidibird' => 'DPEX'],
        'd速' => ['juhe' => 'ds', 'kuaidi100' => 'dsukuaidi', 'kuaidibird' => 'DSWL'],
        'fedex国内' => ['juhe' => 'fedexcn', 'kuaidi100' => 'fedexcn', 'kuaidibird' => 'FEDEX'],
        'fedex国际' => ['juhe' => 'fedexcn', 'kuaidi100' => 'fedex', 'kuaidibird' => 'FEDEX_GJ'],
        'ocs' => ['juhe' => 'ocs', 'kuaidi100' => 'ocs', 'kuaidibird' => ''],
        'tnt' => ['juhe' => 'tnt', 'kuaidi100' => 'tnt', 'kuaidibird' => 'TNT'],
        '中国东方' => ['juhe' => 'coe', 'kuaidi100' => 'coe', 'kuaidibird' => ''],
        '传喜' => ['juhe' => 'cxwl', 'kuaidi100' => 'chuanxiwuliu', 'kuaidibird' => 'CXHY'],
        '城市100' => ['juhe' => 'cs', 'kuaidi100' => 'city100', 'kuaidibird' => 'CITY100'],
        '城市之星' => ['juhe' => 'cszx', 'kuaidi100' => '', 'kuaidibird' => ''],
        '安捷' => ['juhe' => 'aj', 'kuaidi100' => 'anjie88', 'kuaidibird' => 'AJ'],
        '百福东方' => ['juhe' => 'bfdf', 'kuaidi100' => 'baifudongfang', 'kuaidibird' => 'BFDF'],
        '程光' => ['juhe' => 'chengguang', 'kuaidi100' => 'chengguangkuaidi', 'kuaidibird' => 'CG'],
        '递四方' => ['juhe' => 'dsf', 'kuaidi100' => 'disifang', 'kuaidibird' => 'D4PX'],
        '长通' => ['juhe' => 'ctwl', 'kuaidi100' => '', 'kuaidibird' => ''],
        '飞豹' => ['juhe' => 'feibao', 'kuaidi100' => 'feibaokuaidi', 'kuaidibird' => ''],
        '安能' => ['juhe' => 'ane66', 'kuaidi100' => 'annengwuliu', 'kuaidibird' => 'ANE'],
        '远成' => ['juhe' => 'youzheng', 'kuaidi100' => 'youzhengbk', 'kuaidibird' => 'YZPY'],
        '百世' => ['juhe' => 'bsky', 'kuaidi100' => 'huitongkuaidi', 'kuaidibird' => 'HTKY'],
        '苏宁' => ['juhe' => 'suning', 'kuaidi100' => 'suning', 'kuaidibird' => 'SNWL'],
        '九曳' => ['juhe' => 'jiuye', 'kuaidi100' => 'jiuyescm', 'kuaidibird' => 'JIUYE'],
        '亚马逊' => ['juhe' => '', 'kuaidi100' => '', 'kuaidibird' => 'AMAZON'],
        '环球速运' => ['juhe' => '', 'kuaidi100' => '', 'kuaibird' => 'HQSY'],
    ];

    /**
     * 获取物流公司编码
     *
     * @param string $channel
     * @param string $code
     * @param string $companyName
     *
     * @return string
     *
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    public function getCode(string $channel, string $code, string $companyName = ''): string
    {
        $url = 'http://m.kuaidi100.com/autonumber/autoComNum';
        $params = ['resultv2' => 1, 'text' => $code];
        $companyCode = '';
        $channelName = strtolower($channel);
        $companyCodeInfo = $this->get($url, $params);
        $companyCodeArr = \json_decode($companyCodeInfo, true);
        if (isset($companyCodeArr['auto'])) {
            $kuaidi100CompanyCodeArr = \array_column($companyCodeArr['auto'], 'comCode');
            $kuaidi100CompanyCode = $kuaidi100CompanyCodeArr[0];
        }
        foreach ($this->companyList as $name => $item) {
            if (isset($kuaidi100CompanyCode) && $item['kuaidi100'] === $kuaidi100CompanyCode) {
                $companyCode = $item[$channelName];
            } else {
                if ($companyName) {
                    $pattern = "/($name)(\w+)/i";
                    if (1 === \preg_match($pattern, $companyName)) {
                        $companyCode = $this->companyList[$name][$channelName];
                    }
                }
            }
        }

        return $companyCode;
    }
}
