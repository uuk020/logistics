<?php
namespace Wythe\Logistics;

use Wythe\Logistics\Exceptions\NoQueryAvailableException;

class Logistics
{

    private $queryList = [
        'baidu' => '\Wythe\Logistics\Query\BaiduQuery',
        'kuaidi100' => '\Wythe\Logistics\Query\Kuaidi100Query'
    ];

    /**
     * 获取发货时间
     *
     * @param string $code
     * @param string $type
     * @return string
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function getSendingTime(string $code, string $type = '')
    {
        $logistics = $this->getLogistics($code, $type);
        $data = [];
        if ($logistics['data']) {
            $data = $logistics[count($logistics) - 1];
        }
        return $data['time'];
    }

    /**
     * 获取物流信息
     *
     * @param string $code
     * @param string $type
     * @return array
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function getLogistics(string $code, string $type ='')
    {
        $results = [];
        $isSuccessful = false;
        foreach ($this->queryList as $key => $class) {
            $results[$key]['from'] = $key;
            try {
                $results[$key] = (new $class())->callInterface($code, $type);
                $isSuccessful = true;
                break;
            } catch (\Exception $exception) {
                $results[$key]['exception'] = $exception->getMessage();
            }
        }

        if (!$isSuccessful) {
            throw new NoQueryAvailableException($results);
        }
        return $results;
    }

}