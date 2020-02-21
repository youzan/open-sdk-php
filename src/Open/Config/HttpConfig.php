<?php

namespace Youzan\Open\Config;

class HttpConfig
{

    const FORMAT_USER_AGENT = 'YZY-Open-Client %s-PHP';

    const SDK_VERSION = '2.0.19';

    const REQUEST_BASE_URL = 'https://open.youzanyun.com';

    const REQUEST_PATH = '/api/%s/%s?access_token=%s';

    const REQUEST_PATH_AUTH_EXEMPT = '/api/auth_exempt/%s/%s';

    const REQUEST_PATH_TEXTAREA = '/api/_textarea_/%s/%s?access_token=%s';

    const REQUEST_PATH_TOKEN = '/auth/token';


    public static function buildRequestUrl($api, $version, $accessToken = null, $config = [])
    {
        $path = empty($accessToken) ? self::REQUEST_PATH_AUTH_EXEMPT : self::REQUEST_PATH;
        if (!empty($config) && isset($config['isRichText']) && $config['isRichText']) {
            $path = self::REQUEST_PATH_TEXTAREA;
        }

        $baseUrl = isset($config['baseUrl']) ? $config['baseUrl'] : self::REQUEST_BASE_URL;

        return $baseUrl . sprintf($path, $api, $version, $accessToken);
    }

    public static function buildTokenUrl($config = [])
    {
        $baseUrl = isset($config['baseUrl']) ? $config['baseUrl'] : self::REQUEST_BASE_URL;

        return $baseUrl . self::REQUEST_PATH_TOKEN;
    }


    public static function buildHttpHeaders()
    {
        return [
            'User-Agent' => sprintf(self::FORMAT_USER_AGENT, self::SDK_VERSION),
        ];
    }

}