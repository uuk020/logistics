<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/6/23
 * Time: 0:17.
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

/**
 * 聚合数据 查询物流接口.
 */
class JuHeChannel extends Channel
{
    /**
     * JuHeChannel constructor.
     */
    public function __construct()
    {
        $this->url = 'http://v.juhe.cn/exp/index';
    }

    /**
     * 构造请求参数.
     *
     * @param string $code
     * @param string $company
     *
     * @return array
     *
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    private function setRequestParam(string $code, string $company): array
    {
        $config = $this->getChannelConfig();
        $companyCode = (new \Wythe\Logistics\SupportLogistics())->getCode($this->getClassName(), $code, $company);

        return ['key' => $config['app_key'], 'com' => $companyCode];
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
            $params = $this->setRequestParam($code, $company);
            $params['no'] = $code;
            $response = $this->get($this->url, $params);
            $this->toArray($response);
            $this->format();

            return $this->response;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
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
            if (0 === $jsonToArray['error_code']) {
                $this->response = [
                    'status' => 1,
                    'message' => 'ok',
                    'error_code' => 0,
                    'data' => $jsonToArray['result']['list'],
                    'logistics_company' => $jsonToArray['result']['company'],
                ];
            } else {
                $this->response = [
                    'status' => 0,
                    'message' => $jsonToArray['reason'],
                    'error_code' => $jsonToArray['error_code'],
                    'data' => [],
                    'logistics_company' => '',
                ];
            }
        }
    }

    /**
     * 格式化数组.
     */
    protected function format()
    {
        if (!empty($this->response['data'])) {
            $formatData = [];
            foreach ($this->response['data'] as $datum) {
                $formatData[] = ['time' => $datum['datetime'], 'description' => $datum['remark']];
            }
            $this->response['data'] = $formatData;
        }
    }
}
