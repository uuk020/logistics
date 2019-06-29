<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/6/21
 * Time: 23:31.
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

namespace Wythe\Logistics\Channel;

use Wythe\Logistics\Traits\HttpRequest;

/**
 * 快递鸟查询物流接口.
 */
class KuaiDiBirdChannel extends Channel
{
    use HttpRequest;

    /**
     * 增值请求指令.
     *
     * @var int
     */
    const PAYED = 8001;

    /**
     * 免费请求指令.
     *
     * @var int
     */
    const FREE = 1002;

    /**
     * KuaiDiBirdChannel constructor.
     */
    public function __construct()
    {
        $this->url = 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';
    }

    /**
     * 拼接请求URL链接.
     *
     * @param string $requestData 请求的数据
     *
     * @return array
     */
    public function setRequestParam(string $requestData): array
    {
        return [
            'EBusinessID' => $this->getChannelConfig()['app_secret'],
            'DataType' => 2,
            'RequestType' => $this->getChannelConfig()['vip'] ? self::PAYED : self::FREE,
            'RequestData' => \urlencode($requestData),
            'DataSign' => $this->encrypt($requestData, $this->getChannelConfig()['app_key']),
        ];
    }

    /**
     * 编码
     *
     * @param string $data
     * @param string $appKey
     *
     * @return string
     */
    private function encrypt(string $data, string $appKey): string
    {
        return \urlencode(\base64_encode(\md5($data.$appKey)));
    }

    /**
     * 请求
     *
     * @param string $code
     * @param string $company
     *
     * @return array
     *
     * @throws \Exception
     */
    public function request(string $code, string $company = ''): array
    {
        try {
            $companyCode = (new \Wythe\Logistics\SupportLogistics())->getCode($this->getClassName(), $code, $company);
            $requestData = $this->setRequestParam(\json_encode(['OrderCode' => '', 'ShipperCode' => $companyCode, 'LogisticCode' => $code]));
            $response = $this->post($this->url, $requestData, ['header' => 'application/x-www-form-urlencoded;charset=utf-8']);
            $this->toArray($response);
            $this->format();

            return $this->response;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * 格式化.
     */
    protected function format()
    {
        if (!empty($this->response['data'])) {
            $formatData = [];
            foreach ($this->response['data'] as $datum) {
                $formatData[] = ['time' => str_replace('/', '-', $datum['AcceptTime']), 'description' => $datum['AcceptStation']];
            }
            $this->response['data'] = $formatData;
        }
    }

    /**
     * 转换为数组.
     *
     * @param array|string $response
     */
    protected function toArray($response)
    {
        $jsonToArray = \json_decode($response, true);
        if (empty($jsonToArray)) {
            $this->response = [
                'status' => 0,
                'message' => '请求发生不知名错误, 查询不到物流信息',
                'error_code' => 0,
                'data' => [],
                'logistics_company' => '',
            ];
        } else {
            if ($jsonToArray['Success']) {
                $this->response = [
                    'status' => 1,
                    'message' => 'ok',
                    'error_code' => 0,
                    'data' => $jsonToArray['Traces'],
                    'logistics_company' => $jsonToArray['ShipperCode'],
                ];
            } else {
                $this->response = [
                    'status' => 0,
                    'message' => $jsonToArray['Reason'],
                    'error_code' => $jsonToArray['State'],
                    'data' => [],
                    'logistics_company' => '',
                ];
            }
        }
    }
}
