<?php
namespace Wythe\Logistics;

use Wythe\Logistics\Exceptions\InvalidArgumentException;
use Wythe\Logistics\Exceptions\NoQueryAvailableException;

class Logistics
{
    const SUCCESS = 'success';

    const FAILURE = 'failure';

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
                    'result' => $this->factory->createChannel($channel)->get($code),
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