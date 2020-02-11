<?php


namespace Youzan\Open\Config;


class HttpConfig
{

    const REQUEST_URL = 'http://bifrost-gateway.qa.s.qima-inc.com/api/%s/%s?access_token=%s';

    const REQUEST_URL_AUTH_EXEMPT = 'http://bifrost-gateway.qa.s.qima-inc.com/api/auth_exempt/%s/%s';

    const REQUEST_URL_TEXTAREA = 'http://bifrost-gateway.qa.s.qima-inc.com/api/_textarea_/%s/%s?access_token=%s';

    const REQUEST_TOKEN_URL = 'http://bifrost-oauth.qa.s.qima-inc.com/auth/token';


    private static $formatUserAgent = 'YZY-Open-Client %s-PHP';


    public static function getHttpHeaders()
    {
        return [
            'User-Agent' => sprintf(self::$formatUserAgent, CommonConfig::SDK_VERSION),
        ];
    }

}