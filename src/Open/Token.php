<?php

namespace Youzan\Open;


use Youzan\Open\Config\HttpConfig;


class Token
{
    private $clientId;
    private $clientSecret;
    private $accessToken;
    private $refreshToken;


    public function __construct($clientId, $clientSecret, $accessToken = null, $refreshToken = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }


    public function getToken($type, $keys = [])
    {
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        switch ($type) {
            // 自用型应用获取 token
            case 'silent':
                $params['authorize_type'] = 'silent';
                $params['grant_id'] = $keys['kdt_id'];
                $params['refresh'] = array_key_exists('refresh', $keys) ? boolval($keys['refresh']) : false;
                break;
            // 工具型应用获取 token
            case 'authorization_code':
                $params['code'] = $keys['code'];
                $params['authorize_type'] = 'authorization_code';
                break;
            // 刷新 token
            case 'refresh_token':
                $params['authorize_type'] = 'refresh_token';
                $params['refresh_token'] = $keys['refresh_token'];
                break;
            default:
                break;
        }

        return $this->parseResponse(
            Http::post(HttpConfig::REQUEST_TOKEN_URL, $params)
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