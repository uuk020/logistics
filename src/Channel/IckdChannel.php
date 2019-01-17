<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/1/9
 * Time: 22:09
 */

namespace Wythe\Logistics\Channel;


use Wythe\Logistics\Exceptions\HttpException;

class IckdChannel extends Channel
{
    public function __construct()
    {
        $this->url = 'https://biz.trace.ickd.cn/auto/';
    }

    /**
     * 生成随机码
     *
     * @return string
     */
    private function randCode(): string
    {
        $letterOfAlphabet = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $index = mt_rand(0, strlen($letterOfAlphabet) - 1);
            $code .= $letterOfAlphabet[$index];
        }
        return $code;
    }

    /**
     * 调用爱查快递查询快递链接
     *
     * @param string $code
     * @return array
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    public function get(string $code): array
    {
        try {
            $urlParams = [
                'mailNo' => $code,
                'spellName' => '',
                'exp-textName' => '',
                'tk' => $this->randCode(),
                'tm' => time() - 1,
                'callback' => '_jqjsp',
                '_'.time()
            ];
            $response = $this->request($this->url.$code, $urlParams, 0, ['referer: https://biz.trace.ickd.cn']);
            $this->format($this->toArray($response));
            return $this->response;
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage());
        }
    }

    /**
     * 转为数组
     *
     * @param array|string $response
     */
    protected function toArray($response)
    {
        $pattern = '/(\_jqjsp\()({.*})\)/i';
        if (preg_match($pattern, $response, $match)) {
            $response = \json_decode($match[2], true);
            $this->response = [
                'status'  => $response['status'],
                'message' => $response['message'],
                'error_code' => $response['errCode'] ?? '',
                'data' => $response['data'] ?? '',
                'logistics_company' => $response['expTextName'] ?? '',
                'logistics_bill_no' => $response['mailNo']
            ];
        } else {
            $this->response = [
                'status' => -1,
                'message' => '查询不到数据',
                'error_code' => -1,
                'data' => '',
                'logistics_company' => ''
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
        if (!empty($this->response['data']) && is_array($this->response['data'])) {
            $formatData = [];
            foreach ($this->response['data'] as $datum) {
                $formatData[] = ['time' => $datum['time'], 'description' => $datum['context']];
            }
            $this->response['data'] = $formatData;
        }
    }
}