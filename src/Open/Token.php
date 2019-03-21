<?php

namespace Youzan\Open;


class Token
{
    private $clientId;
    private $clientSecret;
    private $accessToken;
    private $refreshToken;

    private static $requestUrl = 'https://api.youzanyun.com/auth/token';

    public function __construct($clientId, $clientSecret, $accessToken = null, $refreshToken = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }


    public function getToken($type, $keys = array())
    {
        $params = array();
        $params['client_id'] = $this->clientId;
        $params['client_secret'] = $this->clientSecret;
        if ($type === 'authorization_code') { // 工具型应用获取 token
            $params['authorize_type'] = 'authorization_code';
            $params['code'] = $keys['code'];
            $params['redirect_uri'] = $keys['redirect_uri'];
        } elseif ($type === 'refresh_token') { // 工具型应用刷新 token
            $params['authorize_type'] = 'refresh_token';
            $params['refresh_token'] = $keys['refresh_token'];
            $params['scope'] = 'scope';
        } elseif ($type === 'silent') { // 自用型应用获取 token
            $params['authorize_type'] = 'silent';
            $params['grant_id'] = $keys['kdt_id'];
        } elseif ($type === 'platform_init') {
            $params['grant_type'] = 'authorize_platform';
        } elseif ($type === 'platform') {
            $params['grant_type'] = 'authorize_platform';
            $params['kdt_id'] = $keys['kdt_id'];
        }

        return $this->parseResponse(
            Http::post(self::$requestUrl, $params)
        );
    }

    private function parseResponse($responseData)
    {
        $data = json_decode($responseData, true);
        if (isset($data['success']) && $data['success']) {
            return $data['data'];
        }
        return $data;
    }
}