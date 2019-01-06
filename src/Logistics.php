<?php
namespace Wythe\Logistics;

use Wythe\Logistics\Exceptions\InvalidArgumentException;
use Wythe\Logistics\Exceptions\NoQueryAvailableException;

class Logistics
{
    protected $factory;

    /**
     *
     * Logistics constructor.
     */
    public function __construct()
    {
        $this->factory = new Factory();
    }

    /**
     * 通过接口名称获取物流信息
     *
     * @param string $code
     * @param string $queryName
     * @return array
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     */
    public function getLogisticsByName(string $code, string $queryName = ''): array
    {
        if (empty($code)) {
            throw new InvalidArgumentException('code arguments cannot empty.');
        }
        $results = $this->factory->getInstance($queryName)->callInterface($code);
        return $results;
    }

    /**
     * 通过接口数组获取物流信息
     *
     * @param string $code
     * @param array  $queryArray
     * @return array
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function getLogisticsByArray(string $code, array $queryArray = ['kuaidi100', 'baidu']): array
    {
        $results = [];
        $isSuccessful = false;
        if (empty($code)) {
            throw new InvalidArgumentException('code arguments cannot empty.');
        }
        foreach ($queryArray as $class) {
            try {
                $results[$class]['info'] = $this->factory->getInstance($class)->callInterface($code);
                $isSuccessful = true;
                break;
            } catch (\Exception $exception) {
                $results[$class]['exception'] = $exception->getMessage();
            }
        }
        if (!$isSuccessful) {
            throw new NoQueryAvailableException('sorry! no query class available');
        }
        return $results;
    }
}