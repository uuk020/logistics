<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2018/12/27
 * Time: 22:37
 */

namespace Wythe\Logistics\Query;


use Wythe\Logistics\Exceptions\HttpException;

class Curl
{
    /**
     * curl调用(单线程)
     *
     * @param string       $url
     * @param string|array $params
     * @param int          $isPost
     * @return string
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    public function sendRequest($url, $params, int $isPost = 0)
    {
        $handle = \curl_init();
        $this->setCurlCommonOption($handle);
        $this->setCurlUrlMethod($handle, $url, $params, $isPost);
        $response = \curl_exec($handle);
        if ($response === false) {
            throw new HttpException('请求接口发生错误');
        }
        \curl_close($handle);
        return $response;
    }

    /**
     * 设置cURL参数
     *
     * @param resource $handle
     */
    private function setCurlCommonOption($handle): void
    {
        \curl_setopt($handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        \curl_setopt($handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        \curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        \curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        \curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        \curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
    }

    /**
     * 设置请求方式
     *
     * @param     $handle
     * @param     $url
     * @param     $params
     * @param int $isPost
     */
    private function setCurlUrlMethod($handle, $url, $params, int $isPost = 0)
    {
        if ($isPost === 1) {
            \curl_setopt($handle, CURLOPT_POST, true);
            \curl_setopt($handle, CURLOPT_POSTFIELDS, $params);
            \curl_setopt($handle, CURLOPT_URL, $url);
        } else {
            if (!empty($params)) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                \curl_setopt($handle, CURLOPT_URL, $url . '?' . $params);
            } else {
                \curl_setopt($handle, CURLOPT_URL, $url);
            }
        }
    }

    /**
     * curl 多线程调用
     *
     * @param array $urls
     * @param array $params
     * @param int   $isPost
     * @return array
     */
    public function sendRequestWithUrls(array $urls = [], array $params = [], int $isPost = 0): array
    {
        $mh = \curl_multi_init();
        $result = $responses = [];
        foreach ($urls as $key => $url) {
            $handles[$key] = \curl_init();
            $this->setCurlCommonOption($handles[$key]);
            $this->setCurlOption($handles[$key], $url, $params[$key], $isPost);
            \curl_multi_add_handle($mh, $handles[$key]);
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
}