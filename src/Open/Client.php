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

    public function post($method, $apiVersion, $params = [], $files = [])
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
            return sprintf(HttpConfig::REQUEST_URL_AUTH_EXEMPT, $method, $apiVersion);
        } else {
            return sprintf(HttpConfig::REQUEST_URL, $method, $apiVersion, $this->accessToken);
        }
    }

    public function get($method, $apiVersion, $params = [], $files = [])
    {
        return $this->post($method, $apiVersion, $params, $files);
    }

    private function parseResponse($responseData)
    {
        return json_decode($responseData, true);
    }

}