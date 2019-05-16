<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2018/12/24
 * Time: 21:32
 */

declare(strict_types = 1);
namespace Wythe\Logistics\Channel;

use Wythe\Logistics\Traits\HttpRequest;

abstract class Channel
{
    /**
     * HTTP 请求
     */
    use HttpRequest;

    /**
     * 渠道URL
     *
     * @var string
     */
    protected $url;

    /**
     * 请求资源
     *
     * @var array
     */
    protected $response;

    /**
     * 请求选项
     *
     * @var array
     */
    protected $option = [];

    /**
     * 设置请求选项
     *
     * @param array $option
     * @return \Wythe\Logistics\Channel\Channel
     */
    public function setRequestOption(array $option): Channel
    {
        if (!empty($this->option)) {
            if (isset($option['header']) && isset($this->option['header'])) {
                $this->option['header'] = array_merge($this->option['header'], $option['header']);
            }
            if (isset($option['proxy'])) {
                $this->option['proxy'] = $option['proxy'];
            }
        } else {
            $this->option = $option;
        }
        return $this;
    }

    /**
     * 调用查询接口
     *
     * @param string $code
     * @return array
     */
    abstract public function request(string $code):array ;

    /**
     * 转换为数组
     *
     * @param string|array $response
     * @return void
     */
    abstract protected function toArray($response);

    /**
     * 格式物流信息
     *
     * @return mixed
     */
    abstract protected function format();
}