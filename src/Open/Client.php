<?php

namespace Youzan\Open;

use Youzan\Open\Config\HttpConfig;

class Client
{


    private $accessToken;


    public function __construct($accessToken = null)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * 调用接口 GET
     *
     * @param $api
     * @param $version
     * @param array $params
     * @param array $files
     * @return mixed
     * @deprecated 已废弃, 请使用 post 方法
     * @see self::post
     */
    public function get($api, $version, $params = [], $files = [])
    {
        return $this->post($api, $version, $params, $files);
    }


    /**
     * 调用接口 POST
     *
     * @param $api
     * @param $version
     * @param array $params
     * @param array $files
     * @param array $config
     * @return mixed
     */
    public function post($api, $version, $params = [], $files = [], $config = [])
    {
        $url = HttpConfig::buildRequestUrl($api, $version, $this->accessToken, $config);

        return $this->parseResponse(Http::post($url, $params, $files));
    }


    private function parseResponse($responseData)
    {
        return json_decode($responseData, true);
    }

}