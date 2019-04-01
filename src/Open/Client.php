<?php

namespace Youzan\Open;


class Client
{
    private static $requestUrl = 'https://api.youzanyun.com/api/%s/%s?access_token=%s';
    private static $requestUrlAuthExempt = 'https://api.youzanyun.com/api/auth_exempt/%s/%s';

    private $accessToken;

    public function __construct($accessToken = '')
    {
        $this->accessToken = $accessToken;
    }

    public function post($method, $apiVersion, $params = array(), $files = array())
    {
        return $this->parseResponse(
            Http::post(
                $this->url($method, $apiVersion), $params, $files
            )
        );
    }

    public function url($method, $apiVersion)
    {
        if (empty($this->accessToken)) {
            return sprintf(self::$requestUrlAuthExempt, $method, $apiVersion);
        } else {
            return sprintf(self::$requestUrl, $method, $apiVersion, $this->accessToken);
        }
    }

    private function parseResponse($responseData)
    {
        return json_decode($responseData, true);
    }

}