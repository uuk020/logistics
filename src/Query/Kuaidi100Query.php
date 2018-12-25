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
     * @param string $type
     * @return array
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    public function callInterface(string $code, string $type = ''): array
    {
        try {
            if (empty($code) || empty($type)) {
                throw new InvalidArgumentException('$code和$type参数不能为空');
            }
            $urlParams = ['type' => $type, 'postid' => $code];
            $result = $this->format($this->curl($this->url, $urlParams));
            if ($result['response_status'] !== 200) {
                throw new HttpException($result['message']);
            }
            $this->response['status'] = 1;
            $this->response['message'] = 'OK';
            $this->response['data'] = $result['data'];
            $this->response['logistics_company'] = $result['logistics_company'];
            $this->response['logistics_bill_no'] = $result['logistics_bill_no'];
            return $this->response;
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage());
        }
    }

    /**
     * 格式响应数据
     *
     * @param string $response
     * @return array
     */
    public function format(string $response): array
    {
        $response = \json_decode($response, true);
        $formatData = [
            'response_status'  => $response['status'],
            'message' => $response['message'],
            'error_code' => $response['state'] ?? '',
            'data' => $response['data'] ?? '',
            'logistics_company' => $response['com'] ?? '',
            'logistics_bill_no' => $response['nu'] ?? '',
        ];
        return $formatData;
    }
}