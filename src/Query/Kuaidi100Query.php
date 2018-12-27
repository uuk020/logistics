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
        $this->curl = new Curl();
    }

    /**
     * 调用快递100接口
     *
     * @param string $code
     * @param string $type
     * @return array
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    public function callInterface(string $code, string $type = ''): array
    {
        try {
            $companyCodes = $this->getCompanyCode($code);
            $urlParams = $urls = [];
            foreach ($companyCodes as $companyCode) {
                $urlParams[] = ['type' => $companyCode, 'postid' => $code];
                $urls[] = $this->url;
            }
            $results = $this->format($this->curl->sendRequestWithUrls($urls, $urlParams));
            foreach ($results as $result) {
                $this->response[] = [
                    'status' => $result['response_status'] !== 200 ? 0 : 1,
                    'message' => $result['response_status'] !== 200 ? $result['message'] : 'OK',
                    'data' => $result['data'],
                    'logistics_company' => $result['logistics_company'],
                    'logistics_bill_no' => $result['logistics_bill_no'],
                ];
            }
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
        $response = $this->curl->sendRequest($this->autoGetCompanyNameByUrl, $params);
        $getCompanyInfo = \json_decode($response, true);
        return array_column($getCompanyInfo['auto'], 'comCode');
    }

    /**
     * 格式响应数据
     *
     * @param array $response
     * @return array
     */
    protected function format($response): array
    {
        $formatData = [];
        foreach ($response as $item) {
            if (empty($item['error'])) {
                $formatData[] = [
                    'response_status'  => $item['result']['status'],
                    'message' => $item['result']['message'],
                    'error_code' => $item['result']['state'] ?? '',
                    'data' => $item['result']['data'] ?? '',
                    'logistics_company' => $item['result']['com'] ?? '',
                    'logistics_bill_no' => $item['result']['nu'] ?? '',
                ];
            }
        }
        return $formatData;
    }
}