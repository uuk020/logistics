<?php
namespace Wythe\Logistics;

use Wythe\Logistics\Exceptions\InvalidArgumentException;
use Wythe\Logistics\Exceptions\NoQueryAvailableException;

class Logistics
{
    protected $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * 获取物流信息
     *
     * @param string $code
     * @param string $type
     * @param  array $queryList
     * @return array
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function getLogistics(string $code, string $type ='', array $queryList =[])
    {
        $results = [];
        $isSuccessful = false;
        if (empty($code)) {
            throw new InvalidArgumentException('$code参数不能为空');
        }
        if (empty($queryList)) {
            $results = $this->factory->getInstanceWithName('baidu')->callInterface($code, $type);
            return $results;
        }
        foreach ($queryList as $class) {
            $results[$class]['from'] = $class;
            try {
                $results[$class] = $this->factory->getInstanceWithName($class)->callInterface($code, $type);
                $isSuccessful = true;
                break;
            } catch (\Exception $exception) {
                $results[$class]['exception'] = $exception->getMessage();
            }
        }
        if (!$isSuccessful) {
            throw new NoQueryAvailableException('全部接口获取失败!');
        }
        return $results;
    }

}