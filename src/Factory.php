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
use Wythe\Logistics\Query\Query;

class Factory
{
    private $default = 'baidu';

    protected $interfaces = [];

    /**
     * 获取默认查询类名称
     *
     * @return string
     * @throws \Wythe\Logistics\Exceptions\Exception
     */
    public function getDefault():string
    {
        if (empty($this->default)) {
            throw new Exception('No default query class name configured.');
        }
        return $this->default;
    }

    /**
     * 设置默认查询类名称
     *
     * @param $name
     */
    public function setDefault($name)
    {
        $this->default = $name;
    }

    /**
     * 数组元素存储查询对象
     *
     * @param string $name
     * @return mixed
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     */
    public function getInstance(string $name = '')
    {
        $name = $name ?: $this->default;
        if (!isset($this->queryList[$name])) {
            $className = $this->formatClassName($name);
            if (!class_exists($className)) {
                throw new InvalidArgumentException(sprintf('Class "%s" not exists.', $className));
            }
            $instance = new $className();
            if (!($instance instanceof Query)) {
                throw new InvalidArgumentException(sprintf('Class "%s" not inherited from %s.', $name, Query::class));
            }
            $this->interfaces[$name] = $instance;
        }
        return $this->interfaces[$name];
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
        return __NAMESPACE__ . "\\Query\\{$name}Query";
    }
}