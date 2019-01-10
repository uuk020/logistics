<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2018/12/28
 * Time: 22:46
 */

namespace Wythe\Logistics;

use Wythe\Logistics\Exceptions\InvalidArgumentException;
use Wythe\Logistics\Exceptions\Exception;
use Wythe\Logistics\Channel\Channel;

class Factory
{
    private $defaultChannel = 'baidu';

    protected $channels = [];

    /**
     * 获取默认查询类名称
     *
     * @return string
     * @throws \Wythe\Logistics\Exceptions\Exception
     */
    public function getDefault():string
    {
        if (empty($this->defaultChannel)) {
            throw new Exception('No default query class name configured.');
        }
        return $this->defaultChannel;
    }

    /**
     * 设置默认查询类名称
     *
     * @param $name
     */
    public function setDefault($name)
    {
        $this->defaultChannel = $name;
    }

    /**
     * 数组元素存储查询对象
     *
     * @param string $name
     * @return mixed
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     */
    public function createChannel(string $name = '')
    {
        $name = $name ?: $this->defaultChannel;
        if (!isset($this->channels[$name])) {
            $className = $this->formatClassName($name);
            if (!class_exists($className)) {
                throw new InvalidArgumentException(sprintf('Class "%s" not exists.', $className));
            }
            $instance = new $className();
            if (!($instance instanceof Channel)) {
                throw new InvalidArgumentException(sprintf('Class "%s" not inherited from %s.', $name, Channel::class));
            }
            $this->channels[$name] = $instance;
        }
        return $this->channels[$name];
    }

    /**
     * 格式化类的名称
     *
     * @param string $name
     * @return string
     */
    protected function formatClassName(string $name): string
    {
        if (class_exists($name)) {
            return $name;
        }
        $name = ucfirst(str_replace(['-', '_', ' '], '', $name));
        return __NAMESPACE__ . "\\Channel\\{$name}Channel";
    }
}
