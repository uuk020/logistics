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
    private $defaultQueryClass;

    protected $queryList = [];

    /**
     * 获取默认查询类名称
     *
     * @return string
     * @throws \Wythe\Logistics\Exceptions\Exception
     */
    public function getDefaultQueryClass():string
    {
        if ($this->defaultQueryClass) {
            throw new Exception('No default query class name configured.');
        }
        return $this->defaultQueryClass;
    }

    /**
     * 设置默认查询类名称
     *
     * @param $name
     */
    public function setDefaultQueryClass($name)
    {
        $this->defaultQueryClass = $name;
    }


    /**
     * 数组元素存储查询对象
     *
     * @param string $name
     * @return mixed
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     */
    public function query(string $name = '')
    {
        $name = $name ?: $this->defaultQueryClass;
        if (!isset($this->queryList[$name])) {
            $this->queryList[$name] = $this->makeQueryObject($name);
        }
        return $this->queryList[$name];
    }


    /**
     * 实例化查询对象
     *
     * @param string $name
     * @return mixed
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     */
    protected function makeQueryObject(string $name)
    {
        $className = $this->formatClassName($name);
        $object = new $className();
        if (!($object instanceof Query)) {
            throw new InvalidArgumentException(sprintf('Class "%s" not inherited from %s.', $name, Query::class));
        }
        return $object;
    }

    /**
     * 格式化类的名称
     *
     * @param string $name
     * @return string
     */
    protected function formatClassName(string $name):string
    {
        if (class_exists($name)) {
            return $name;
        }
        $name = ucfirst(str_replace(['-', '_', ' '], '', $name));
        return __NAMESPACE__ . "\\Query\\{$name}Query";
    }
}