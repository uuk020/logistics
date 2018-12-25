<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2018/12/24
 * Time: 21:32
 */

namespace Wythe\Logistics\Query;

use Wythe\Logistics\Exceptions\HttpException;

abstract class Query
{
    /**
     * 查询URL地址
     *
     * @var string
     */
    protected $url = '';

    /**
     * 请求响应信息
     *
     * @var array
     */
    protected $response = ['status' => 0, 'message' => 'error'];

    /**
     * cURL 句柄
     *
     * @var resource
     */
    protected $curlHandle;

    /**
     * curl调用
     *
     * @param string $url
     * @param mixed  $params
     * @param int    $isHttps
     * @param int    $isPost
     * @return mixed
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    protected function curl(string $url, $params = '', int $isHttps = 0, int $isPost = 0)
    {
        $this->curlHandle = \curl_init();
        \curl_setopt($this->curlHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        \curl_setopt($this->curlHandle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        \curl_setopt($this->curlHandle, CURLOPT_CONNECTTIMEOUT, 30);
        \curl_setopt($this->curlHandle, CURLOPT_TIMEOUT, 30);
        \curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);
        if ($isHttps === 1) {
            \curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
            \curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        if ($isPost === 1) {
            \curl_setopt($this->curlHandle, CURLOPT_POST, true);
            \curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $params);
        } else {
            if (!empty($params)) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                \curl_setopt($this->curlHandle, CURLOPT_URL, $url . '?' . $params);
            } else {
                \curl_setopt($this->curlHandle, CURLOPT_URL, $url);
            }
        }
        $response = \curl_exec($this->curlHandle);
        if ($response === false) {
            throw new HttpException('请求接口发生错误');
        }
        \curl_close($this->curlHandle);
        return $response;
    }

    /**
     * 调用查询接口
     *
     * @param string $code
     * @param string $type
     * @return array
     */
    abstract public function callInterface(string $code, string $type = ''):array ;

    /**
     * 格式响应信息
     *
     * @param string $response
     * @return array
     */
    abstract protected function format(string $response):array;
}