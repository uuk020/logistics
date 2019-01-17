<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2018/12/24
 * Time: 21:37
 */

namespace Wythe\Logistics\Channel;


use Wythe\Logistics\Exceptions\Exception;
use Wythe\Logistics\Exceptions\HttpException;
use Wythe\Logistics\Exceptions\InvalidArgumentException;

class Kuaidi100Channel extends Channel
{

    private $autoGetCompanyNameByUrl = 'http://m.kuaidi100.com/autonumber/autoComNum';
    /**
     * 构造函数
     *
     * Kuaidi100Channel constructor.
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
    public function get(string $code): array
    {
        try {
            $companyCodes = $this->getCompanyCode($code);
            $urlParams = $urls = [];
            foreach ($companyCodes as $companyCode) {
                $urlParams[] = ['type' => $companyCode, 'postid' => $code];
                $urls[] = $this->url;
            }
            $response = $this->requestWithUrls($urls, $urlParams);
            $this->format($this->toArray($response));
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
     * 转为数组
     *
     * @param array|string $response
     */
    protected function toArray($response)
    {
        foreach ($response as $item) {
            $data = \json_decode($item['result'], true);
            $this->response[] = [
                'status'  => $data['status'],
                'message' => $data['message'],
                'error_code' => $data['state'] ?? '',
                'data' => $data['data'] ?? '',
                'logistics_company' => $data['com'] ?? '',
                'logistics_bill_no' => $data['nu'] ?? '',
            ];
        }
    }

    /**
     * 统一物流信息
     *
     * @return mixed|void
     */
    protected function format()
    {
        $formatData = [];
        foreach ($this->response as &$item) {
            if (!empty($item['data']) && is_array($item['data'])) {
                foreach ($item['data'] as $datum) {
                    $formatData[] = ['time' => $datum['time'], 'description' => $datum['context']];
                }
                $item['data'] = $formatData;
            }
        }
        unset($item);
    }
}
