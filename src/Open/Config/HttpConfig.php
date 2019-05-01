<?php


namespace Youzan\Open\Config;


class HttpConfig
{

    const REQUEST_URL = 'https://open.youzanyun.com/api/%s/%s?access_token=%s';

    const REQUEST_URL_AUTH_EXEMPT = 'https://open.youzanyun.com/api/auth_exempt/%s/%s';

    const REQUEST_TOKEN_URL = 'https://open.youzanyun.com/auth/token';


    private static $formatUserAgent = 'YZY-Open-Client %s - PHP';


    public static function getHttpHeaders()
    {
        return [
            'User-Agent' => sprintf(self::$formatUserAgent, CommonConfig::SDK_VERSION),
        ];
    }

}