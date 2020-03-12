<?php

namespace Youzan\Open;

use Youzan\Open\Config\HttpConfig;

class Token
{

    private $clientId;
    private $clientSecret;


    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }


    /**
     * 获取自用型应用Token
     *
     * @param int|string $authorityId
     * @param array $config
     * @return mixed
     */
    public function getSelfAppToken($authorityId, $config = [])
    {
        $params = [
            'authorize_type' => 'silent',
            'grant_id' => $authorityId,
            'refresh' => array_key_exists('refresh', $config) ? boolval($config['refresh']) : false,
        ];

        return $this->exec($params, $config);
    }


    /**
     * 获取工具型应用Token(通过code换取)
     *
     * @param string $code
     * @param array $config
     * @return mixed
     */
    public function getToolAppToken($code, $config = [])
    {
        $params = [
            'authorize_type' => 'authorization_code',
            'code' => $code,
        ];

        return $this->exec($params, $config);
    }

    /**
     * 有赞客应用获取Token
     *
     * @param int|string $authorityId
     * @param array $config
     * @return mixed
     */
    public function getYouzanKeAppToken($authorityId, $config = [])
    {
        $params = [
            'authorize_type' => 'silent',
            'grant_id' => $authorityId,
            'grant_type' => 'youzanke',
            'refresh' => array_key_exists('refresh', $config) ? boolval($config['refresh']) : false,
        ];

        return $this->exec($params, $config);
    }

    /**
     * 平台型应用获取Token
     *
     * @param int|string $authorityId
     * @param array $config
     * @return mixed
     */
    public function getPlatformAppToken($authorityId, $config = [])
    {
        $params = [
            'authorize_type' => 'certificate',
            'grant_id' => $authorityId,
            'refresh' => array_key_exists('refresh', $config) ? boolval($config['refresh']) : false,
        ];

        return $this->exec($params, $config);
    }

    /**
     * 刷新Token(通过refreshToken刷新Token)
     *
     * @param string $refreshToken
     * @param array $config
     * @return mixed
     */
    public function refreshToken($refreshToken, $config = [])
    {
        $params = [
            'authorize_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        return $this->exec($params, $config);
    }


    /**
     * 获取Token
     *
     * @param $type
     * @param array $keys
     * @return mixed
     * @deprecated
     */
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
                $params['authorize_type'] = 'authorization_code';
                $params['code'] = $keys['code'];
                break;
            // 刷新 token
            case 'refresh_token':
                $params['authorize_type'] = 'refresh_token';
                $params['refresh_token'] = $keys['refresh_token'];
                break;
            default:
                break;
        }

        $url = HttpConfig::buildTokenUrl($keys);
        return $this->parseResponse(Http::post($url, $params));
    }


    private function exec($params, $config = [])
    {
        $params['client_id'] = $this->clientId;
        $params['client_secret'] = $this->clientSecret;

        $url = HttpConfig::buildTokenUrl($config);
        return $this->parseResponse(Http::post($url, $params));
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