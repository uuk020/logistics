<?php
namespace Wythe\Logistics;

use Wythe\Logistics\Exceptions\InvalidArgumentException;
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
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function getSendingTime(string $code, string $type = ''):string
    {
        $logistics = $this->getLogistics($code, $type);
        $queryName = array_keys($this->queryList);
        $data = [];
        foreach ($queryName as $name) {
            if (isset($logistics[$name])) {
                $logisticsInfo = $logistics[$name]['data'];
                if ($logisticsInfo) {
                    $data = $logisticsInfo[count($logistics) - 1];
                }
            }
            if ($data['time'] == date('Y-m-d', strtotime($data['time']))) {
                $data['time'] = strtotime($data['time']);
            }
            return $data['time'];
        }
        return '';
    }

    /**
     * 获取物流信息
     *
     * @param string $code
     * @param string $type
     * @return array
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function getLogistics(string $code, string $type ='')
    {
        $results = [];
        $isSuccessful = false;
        if (empty($code) || empty($type)) {
            throw new InvalidArgumentException('$code和$type参数不能为空');
        }
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