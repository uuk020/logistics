<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2018/12/28
 * Time: 22:31.
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

namespace Wythe\Logistics\Traits;

use Wythe\Logistics\Exceptions\HttpException;

trait HttpRequest
{
    /**
     * 设置cURL参数.
     *
     * @param resource $handle
     * @param array    $option
     */
    private function setCurlCommonOption($handle, array $option = [])
    {
        \curl_setopt($handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        \curl_setopt($handle, CURLOPT_USERAGENT, $this->setUseragent());
        \curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        \curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        \curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        \curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($option['header']) && is_array($option['header'])) {
            \curl_setopt($handle, CURLOPT_HTTPHEADER, $option['header']);
        }
        if (!empty($option['proxy']) && is_array($option['proxy'])) {
            \curl_setopt($handle, CURLOPT_PROXY, $option['proxy']);
        }
    }

    /**
     * 设置请求方式.
     *
     * @param $handle
     * @param $url
     * @param $params
     */
    private function setCurlUrlMethod($handle, $url, $params)
    {
        if (!empty($params)) {
            if (is_array($params)) {
                $params = http_build_query($params);
            }
            \curl_setopt($handle, CURLOPT_URL, $url.'?'.$params);
        } else {
            \curl_setopt($handle, CURLOPT_URL, $url);
        }
    }

    /**
     * GET请求
     *
     * @param string       $url
     * @param string|array $params
     * @param array        $option
     *
     * @return string
     *
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    protected function get($url, $params, array $option = [])
    {
        $handle = \curl_init();
        $this->setCurlCommonOption($handle, $option);
        $this->setCurlUrlMethod($handle, $url, $params);
        $response = \curl_exec($handle);
        if (false === $response) {
            throw new HttpException('请求接口发生错误');
        }
        \curl_close($handle);

        return $response;
    }

    /**
     * POST请求
     *
     * @param string       $url
     * @param string|array $params
     * @param array        $option
     *
     * @return bool|string
     *
     * @throws \Wythe\Logistics\Exceptions\HttpException
     */
    protected function post($url, $params, array $option = [])
    {
        $handle = \curl_init();
        $this->setCurlCommonOption($handle, $option);
        $this->setCurlUrlMethod($handle, $url, $params, 1);
        $response = \curl_exec($handle);
        if (false === $response) {
            throw new HttpException('请求接口发生错误');
        }
        \curl_close($handle);

        return $response;
    }

    /**
     * 设置useragent.
     *
     * @return string
     */
    private function setUseragent(): string
    {
        $collectionOfUseragent = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; AcooBrowser; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Acoo Browser; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)',
            'Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.5; AOLBuild 4337.35; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
            'Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)',
            'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 2.0.50727; Media Center PC 6.0)',
            'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 1.0.3705; .NET CLR 1.1.4322)',
            'Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.30)',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.3 (Change: 287 c9dfb30)',
            'Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/527+ (KHTML, like Gecko, Safari/419.3) Arora/0.6',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2pre) Gecko/20070215 K-Ninja/2.1.1',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/20080705 Firefox/3.0 Kapiko/3.0',
            'Mozilla/5.0 (X11; Linux i686; U;) Gecko/20070322 Kazehakase/0.4.5',
            'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.8) Gecko Fedora/1.9.0.8-1.fc10 Kazehakase/0.5.6',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.20 (KHTML, like Gecko) Chrome/19.0.1036.7 Safari/535.20',
        ];
        $index = mt_rand(0, count($collectionOfUseragent) - 1);

        return $collectionOfUseragent[$index];
    }

    /**
     * GET 多线程请求
     *
     * @param array $urls
     * @param array $params
     * @param array $option
     *
     * @return array
     */
    protected function getByQueue(array $urls = [], array $params = [], array $option = []): array
    {
        $mh = \curl_multi_init();
        $responses = [];
        foreach ($urls as $key => $url) {
            $handles[$key] = \curl_init();
            if (!empty($option)) {
                $this->setCurlCommonOption($handles[$key], $option);
            } else {
                $this->setCurlCommonOption($handles[$key]);
            }
            if (!empty($params)) {
                $this->setCurlUrlMethod($handles[$key], $url, $params[$key]);
            } else {
                $this->setCurlUrlMethod($handles[$key], $url, '');
            }
            \curl_multi_add_handle($mh, $handles[$key]);
        }
        $active = null;
        do {
            while (\CURLM_CALL_MULTI_PERFORM == ($mrc = \curl_multi_exec($mh, $active)));
            if (\CURLM_OK != $mrc) {
                break;
            }
            while ($done = \curl_multi_info_read($mh)) {
                $error = \curl_error($done['handle']);
                $result = \curl_multi_getcontent($done['handle']);
                $responses[] = compact('error', 'result');
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
     * POST 多线程请求
     *
     * @param array $urls
     * @param array $params
     * @param array $option
     *
     * @return array
     */
    protected function postByQueue(array $urls = [], array $params = [], array $option = []): array
    {
        $mh = \curl_multi_init();
        $responses = [];
        foreach ($urls as $key => $url) {
            $handles[$key] = \curl_init();
            if (!empty($option)) {
                $this->setCurlCommonOption($handles[$key], $option);
            } else {
                $this->setCurlCommonOption($handles[$key]);
            }
            if (!empty($params)) {
                $this->setCurlUrlMethod($handles[$key], $url, $params[$key], 1);
            } else {
                $this->setCurlUrlMethod($handles[$key], $url, '', 1);
            }
            \curl_multi_add_handle($mh, $handles[$key]);
        }
        $active = null;
        do {
            while (\CURLM_CALL_MULTI_PERFORM == ($mrc = \curl_multi_exec($mh, $active)));
            if (\CURLM_OK != $mrc) {
                break;
            }
            while ($done = \curl_multi_info_read($mh)) {
                $error = \curl_error($done['handle']);
                $result = \curl_multi_getcontent($done['handle']);
                $responses[] = compact('error', 'result');
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
