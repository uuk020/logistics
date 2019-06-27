<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/6/21
 * Time: 23:03
 */
declare(strict_types  = 1);
namespace Wythe\Logistics;
/**
 * 配置类
 */
class Config
{
    private $config = [
        'juhe' => ['app_key' => 'app_key', 'vip' => false], // 免费套餐 100 次
        'jisu' => ['app_key' => 'app_key', 'app_secret' => 'app_secret' ,'vip' => false], // 免费套餐 1000 次
        'shujuzhihui' => ['app_key' => 'app_key', 'vip' => false], // 免费套餐 100 次
        'kuaidi100' => ['app_key' => 'app_key', 'app_secret' => 'app_secret', 'vip' => false], // 免费套餐 100 次
        'kuaidibird' => ['app_key' => 'app_key', 'app_secret' => 'app_secret', 'vip' => false], // 免费套餐 3000 次
    ];

    /**
     * 获取配置
     *
     * @param string $key
     * @return array
     */
    public function getConfig(string $key): array
    {
        return $this->config[$key];
    }
}