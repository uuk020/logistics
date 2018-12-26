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
     * cURL 多句柄
     *
     * @var resource
     */
    protected $curlHandles = [];

    /**
     * curl调用(单线程)
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
        $this->setCurlOption($this->curlHandle, $url, $params, $isHttps, $isPost);
        $response = \curl_exec($this->curlHandle);
        if ($response === false) {
            throw new HttpException('请求接口发生错误');
        }
        \curl_close($this->curlHandle);
        return $response;
    }

    /**
     * 设置cURL参数
     *
     * @param resource     $handle
     * @param string       $url
     * @param array|string $params
     * @param int          $isHttps
     * @param int          $isPost
     */
    protected function setCurlOption($handle, string $url, $params, int $isHttps = 0, int $isPost = 0): void
    {
        \curl_setopt($handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        \curl_setopt($handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        \curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        \curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        \curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        if ($isHttps === 1) {
            \curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            \curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($isPost === 1) {
            \curl_setopt($handle, CURLOPT_POST, true);
            \curl_setopt($handle, CURLOPT_POSTFIELDS, $params);
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
    }

    /**
     * curl 多线程调用
     *
     * @param array $urls
     * @param array $params
     * @param int   $isHttps
     * @param int   $isPost
     * @return array
     */
    protected function curlMulti(array $urls, array $params = [], int $isHttps = 0, int $isPost = 0): array
    {
        $mh = \curl_multi_init();
        $result = $responses = [];
        foreach ($urls as $key => $url) {
            $this->curlHandles[$key] = \curl_init($url);
            $this->setCurlOption($this->curlHandles[$key], $url, $params[$key], $isHttps, $isPost);
            \curl_multi_add_handle($mh, $this->curlHandles[$key]);
        }
        $active = null;
        do {
            while (($mrc = \curl_multi_exec($mh, $active)) == \CURLM_CALL_MULTI_PERFORM) ;
            if ($mrc != \CURLM_OK) break;
            while ($done = \curl_multi_info_read($mh)) {
                $info = \curl_getinfo($done['handle']);
                $error = \curl_error($done['handle']);
                $result[] = \curl_multi_getcontent($done['handle']);
                $responses[] = compact('info', 'error', 'result');
                \curl_multi_remove_handle($mh, $done['handle']);
                \curl_close($done['handle']);
            }
            if ($active > 0) {
                \curl_multi_select($mh);
            }
        } while ($active);
        \curl_multi_close($mh);
        return $responses;
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