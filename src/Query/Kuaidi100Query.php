<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2018/12/24
 * Time: 21:37
 */

namespace Wythe\Logistics\Query;


use Wythe\Logistics\Exceptions\Exception;
use Wythe\Logistics\Exceptions\HttpException;
use Wythe\Logistics\Exceptions\InvalidArgumentException;

class Kuaidi100Query extends Query
{

    private $autoGetCompanyNameByUrl = 'http://m.kuaidi100.com/autonumber/autoComNum';
    /**
     * 构造函数
     * Kuaidi100Query constructor.
     */
    public function __construct()
    {
        $this->url = 'http://m.kuaidi100.com/query';
    }

    /**
     * 调用快递100接口
     *
     * @param string $code
     * @return array
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    public function callInterface(string $code): array
    {
        try {
            $companyCodes = $this->getCompanyCode($code);
            $urlParams = $urls = [];
            foreach ($companyCodes as $companyCode) {
                $urlParams[] = ['type' => $companyCode, 'postid' => $code];
                $urls[] = $this->url;
            }
            $this->format($this->requestWithUrls($urls, $urlParams));
            return $this->response;
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage());
        }
    }

    /**
     * 根据运单号获取物流公司名称
     *
     * @param string $code
     * @return array
     * @throws HttpException
     */
    protected function getCompanyCode(string $code): array
    {
        $params = ['resultv2' => 1, 'text' => $code];
        $response = $this->request($this->autoGetCompanyNameByUrl, $params);
        $getCompanyInfo = \json_decode($response, true);
        if (!isset($getCompanyInfo['auto'])) {
            throw new HttpException('no company code');
        }
        return array_column($getCompanyInfo['auto'], 'comCode');
    }

    /**
     * 格式响应数据
     *
     * @param array $response
     * @return array
     */
    protected function format($response): void
    {
        foreach ($response as $item) {
            if (empty($item['error'])) {
                $this->response[] = [
                    'response_status'  => $item['result']['status'],
                    'message' => $item['result']['message'],
                    'error_code' => $item['result']['state'] ?? '',
                    'data' => $item['result']['data'] ?? '',
                    'logistics_company' => $item['result']['com'] ?? '',
                    'logistics_bill_no' => $item['result']['nu'] ?? '',
                ];
            }
        }
    }
}