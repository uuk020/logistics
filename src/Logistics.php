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
            throw new InvalidArgumentException('code arguments cannot empty.');
        }
        if (empty($queryList)) {
            $results = $this->factory->getInstance('baidu')->callInterface($code);
            return $results;
        }
        foreach ($queryList as $class) {
            $results[$class]['from'] = $class;
            try {
                $results[$class] = $this->factory->getInstance($class)->callInterface($code);
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