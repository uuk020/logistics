<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/6/21
 * Time: 23:03.
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

namespace Wythe\Logistics;

use Wythe\Logistics\Exceptions\ConfigNotFoundException;
use Wythe\Logistics\Exceptions\ConfigValidateException;

/**
 * 配置类.
 */
class Config
{
    /**
     * 配置.
     *
     * @var array
     */
    protected static $config;

    /**
     * 配置验证规则.
     *
     * @var array
     */
    protected $validateRule = [
        'juhe' => ['app_key', 'vip'],
        'jisu' => ['app_key', 'vip'],
        'shujuzhihui' => ['app_key', 'vip'],
        'kuaidi100' => ['app_key', 'app_secret'],
        'kuaidibird' => ['app_key', 'app_secret', 'vip'],
    ];

    /**
     * 验证规则.
     *
     * @throws ConfigNotFoundException
     * @throws ConfigValidateException
     */
    protected function validate(string $channel)
    {
        if (!in_array($channel, array_keys($this->validateRule))) {
            throw new ConfigNotFoundException('没找到相对应配置规则');
        }
        $keys = array_keys(static::$config[$channel]);
        $intersect = array_intersect($this->validateRule[$channel], $keys);
        if (count($intersect) !== count($this->validateRule[$channel])) {
            throw new ConfigValidateException('规则验证失败');
        }
    }

    /**
     * 设置配置.
     *
     * @throws ConfigNotFoundException
     * @throws ConfigValidateException
     */
    public function setConfig(array $params)
    {
        static::$config = $params;
        foreach (static::$config as $channel => $param) {
            $this->validate($channel);
        }
    }

    /**
     * 获取配置.
     */
    public function getConfig(string $key): array
    {
        return static::$config[$key];
    }
}
