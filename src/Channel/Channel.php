<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2018/12/24
 * Time: 21:32.
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

namespace Wythe\Logistics\Channel;

use Wythe\Logistics\Config;
use Wythe\Logistics\Traits\HttpRequest;

abstract class Channel
{
    /*
     * HTTP 请求
     */
    use HttpRequest;

    /**
     * 渠道URL.
     *
     * @var string
     */
    protected $url;

    /**
     * 请求资源.
     *
     * @var array
     */
    protected $response;

    /**
     * 请求选项.
     *
     * @var array
     */
    protected $option = [];

    /**
     * 设置请求选项.
     *
     * @param array $option
     *
     * @return \Wythe\Logistics\Channel\Channel
     */
    public function setRequestOption(array $option): self
    {
        if (!empty($this->option)) {
            if (isset($option['header']) && isset($this->option['header'])) {
                $this->option['header'] = array_merge($this->option['header'], $option['header']);
            }
            if (isset($option['proxy'])) {
                $this->option['proxy'] = $option['proxy'];
            }
        } else {
            $this->option = $option;
        }

        return $this;
    }

    /**
     * 获取实例化的类名称.
     *
     * @return string
     */
    protected function getClassName(): string
    {
        $className = basename(str_replace('\\', '/', (get_class($this))));

        return preg_replace('/Channel/', '', $className);
    }

    /**
     * 获取配置.
     *
     * @return array
     */
    protected function getChannelConfig(): array
    {
        $key = $this->getClassName();
        $config = (new Config())->getConfig(strtolower($key));

        return $config;
    }

    /**
     * 调用查询接口.
     *
     * @param string $code
     * @param string $company
     *
     * @return array
     */
    abstract public function request(string $code, string $company = ''): array;

    /**
     * 转换为数组.
     *
     * @param string|array $response
     */
    abstract protected function toArray($response);

    /**
     * 格式物流信息.
     *
     * @return mixed
     */
    abstract protected function format();
}
