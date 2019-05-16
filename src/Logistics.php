<?php
declare(strict_types = 1);
namespace Wythe\Logistics;

use Wythe\Logistics\Exceptions\InvalidArgumentException;
use Wythe\Logistics\Exceptions\NoQueryAvailableException;

/**
 * 抓取物流信息
 * @package Wythe\Logistics
 */
class Logistics
{
    /**
     * 成功
     *
     * @var string
     */
    const SUCCESS = 'success';

    /**
     * 失败
     *
     * @string
     */
    const FAILURE = 'failure';

    /**
     * 快递渠道工厂
     *
     * @var \Wythe\Logistics\Factory
     */
    protected $factory;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->factory = new Factory();
    }

    /**
     * 通过接口获取物流信息
     *
     * @param string $code
     * @param array  $channels
     * @return array
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function query(string $code, $channels = ['kuaidi100']): array
    {
        $results = [];
        if (empty($code)) {
            throw new InvalidArgumentException('code arguments cannot empty.');
        }
        if (!empty($channels) && is_string($channels)) {
            $channels = explode(',', $channels);
        }
        foreach ($channels as $channel) {
            try {
                $results[$channel] = [
                    'channel' => $channel,
                    'status' => self::SUCCESS,
                    'result' => $this->factory->createChannel($channel)->request($code),
                ];
            } catch (\Exception $exception) {
                $results[$channel] = [
                    'channel' => $channel,
                    'status' => self::FAILURE,
                    'exception' => $exception->getMessage(),
                ];
            }
        }
        $collectionOfException = array_column($results, 'exception');
        if (count($collectionOfException) === 3) {
            throw new NoQueryAvailableException('sorry! no channel class available');
        }
        return $results;
    }

    /**
     * 通过代理IP获取物流信息
     *
     * @param array  $proxy
     * @param string $code
     * @param array  $channels
     * @return array
     * @throws \Wythe\Logistics\Exceptions\InvalidArgumentException
     * @throws \Wythe\Logistics\Exceptions\NoQueryAvailableException
     */
    public function queryByProxy(array $proxy, string $code, $channels = ['kuaidi100']): array
    {
        $results = [];
        if (empty($code)) {
            throw new InvalidArgumentException('code arguments cannot empty.');
        }
        if (!empty($channels) && is_string($channels)) {
            $channels = explode(',', $channels);
        }
        foreach ($channels as $channel) {
            try {
                $results[$channel] = [
                    'channel' => $channel,
                    'status' => self::SUCCESS,
                    'result' => $this->factory->createChannel($channel)->setRequestOption($proxy)->request($code),
                ];
            } catch (\Exception $exception) {
                $results[$channel] = [
                    'channel' => $channel,
                    'status' => self::FAILURE,
                    'exception' => $exception->getMessage(),
                ];
            }
        }
        $collectionOfException = array_column($results, 'exception');
        if (count($collectionOfException) === 3) {
            throw new NoQueryAvailableException('sorry! no channel class available');
        }
        return $results;
    }
}