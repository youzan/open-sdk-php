<?php


namespace Youzan\Open\Config;


class EcommerceConfig
{

    const ENV_PROXY_TOKEN = "youzan_proxy_token";

    const ENV_PROXY_HOST = "youzan_proxy_host";

    const ENV_PROXY_ENABLE = "youzan_proxy_enable";

    const ENV_PROXY_NO_PROXY_HOSTS = "youzan_proxy_nonProxyHosts";

    const ENV_PROXY_KEEPALIVE_TIMEOUT = "youzan_proxy_keepaliveTimeout";

    const ENV_PROXY_KEEPALIVE_POOL_SIZE = "youzan_proxy_keepalivePoolSize";

    const ENV_SCHEME = 'https';

    private static $formatUserAgent = 'YZY-Open-Client %s - PHP';


    public static function getHttpProxy()
    {
        return self::getEnvValue(self::ENV_PROXY_HOST);
    }


    public static function getHttpHeaders()
    {
        return [
            'Scheme' => self::ENV_SCHEME,
            'User-Agent' => sprintf(self::$formatUserAgent, CommonConfig::SDK_VERSION),
            'yzc-token' => self::getEnvValue(self::ENV_PROXY_TOKEN),
            'Yzc-Keepalive-Timeout' => self::getEnvValue(self::ENV_PROXY_KEEPALIVE_TIMEOUT),
            'YZC-Keepalive-Poolsize' => self::getEnvValue(self::ENV_PROXY_KEEPALIVE_POOL_SIZE),
        ];
    }

    private static function getEnvValue($key)
    {
        return getenv($key);
    }

}