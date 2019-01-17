<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2018/12/24
 * Time: 21:35
 */

namespace Wythe\Logistics\Channel;


use Wythe\Logistics\Exceptions\HttpException;
use Wythe\Logistics\Exceptions\InvalidArgumentException;

class BaiduChannel extends Channel
{
    /**
     * 构造函数
     *
     * BaiduChannel constructor.
     */
    public function __construct()
    {
        $this->url = 'https://sp0.baidu.com/9_Q4sjW91Qh3otqbppnN2DJv/pae/channel/data/asyncqury';
    }

    /**
     * 调用百度查询快递链接
     *
     * @param string $code
     * @return array
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    public function get(string $code): array
    {
        try {
            $rand = $this->randNumber();
            $urlParams = [
                'cb' => 'jQuery1102027' . $rand[0],
                'appid' => 4001,
                'com' => '',
                'nu'=> $code,
                'vscode' => '',
                'token' => '',
                '_' => $rand[1]
            ];
            $response = $this->request($this->url, $urlParams);
            $this->format($this->toArray($response));
            $this->response['logistics_bill_no'] = $code;
            return $this->response;
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage());
        }
    }

    /**
     * 转为数组
     *
     * @param  $response
     * @return void
     */
    protected function toArray($response)
    {
        $pattern = '/(jQuery\d+_\d+\()({.*})\)$/i';
        if (preg_match($pattern, $response, $match)) {
            $response = \json_decode($match[2], true);
            $this->response = [
                'status'  => $response['status'],
                'message' => $response['msg'],
                'error_code' => $response['error_code'] ?? '',
                'data' => $response['data']['info']['context'] ?? '',
                'logistics_company' => $response['com'] ?? '',
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
                $formatData[] = ['time' => date('Y-m-d H:i:s', $datum['time']), 'description' => $datum['desc']];
            }
            $this->response['data'] = $formatData;
        }
    }

    /**
     * 生成请求随机字符串数组
     *
     * @return array
     */
    private function randNumber(): array
    {
        $str = $subStr = '';
        for ($i = 0; $i < 15; $i++) {
            $str .= \mt_rand(0, 9);
        }
        for ($i = 0; $i < 3; $i++) {
            $subStr .= \mt_rand(0, 9);
        }
        return [$str . '_' . \time() . $subStr, \time() . $subStr];
    }
}