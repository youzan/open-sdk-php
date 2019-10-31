<?php

namespace Youzan\Open;


use Youzan\Open\Config\HttpConfig;


class Client
{

    private $accessToken;

    public function __construct($accessToken = '')
    {
        $this->accessToken = $accessToken;
    }

    public function get($method, $apiVersion, $params = [], $files = [], $config = [])
    {
        return $this->post($method, $apiVersion, $params, $files, $config);
    }

    public function post($method, $apiVersion, $params = [], $files = [], $config = [])
    {
        return $this->parseResponse(
            Http::post(
                $this->url($method, $apiVersion, $config), $params, $files
            )
        );
    }

    private function url($method, $apiVersion, $config = [])
    {
        if (empty($this->accessToken)) {
            return sprintf(HttpConfig::REQUEST_URL_AUTH_EXEMPT, $method, $apiVersion);
        }

        if (!empty($config) && $config['isRichText']) {
            return sprintf(HttpConfig::REQUEST_URL_TEXTAREA, $method, $apiVersion, $this->accessToken);
        }

        return sprintf(HttpConfig::REQUEST_URL, $method, $apiVersion, $this->accessToken);
    }

    private function parseResponse($responseData)
    {
        return json_decode($responseData, true);
    }

}