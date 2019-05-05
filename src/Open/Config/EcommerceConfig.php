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

    const HOST_QIMA = 'qima-inc.com';

    const LOCAL_IP = "/(10|172|192)\\.([0-1][0-9]{0,2}|[2][0-5]{0,2}|[3-9][0-9]{0,1})\\.([0-1][0-9]{0,2}|[2][0-5]{0,2}|[3-9][0-9]{0,1})\\.([0-1][0-9]{0,2}|[2][0-5]{0,2}|[3-9][0-9]{0,1})/";

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


    public static function isUseProxy($host)
    {
        if (self::isInner($host) || self::isInWhiteList($host)) {
            return true;
        }

        return false;
    }


    private static function getEnvValue($key)
    {
        return getenv($key);
    }

    private static function isInner($host)
    {
        if (in_array($host, ['localhost', '127.0.0.1'])) {
            return true;
        }

        if (substr($host, -strlen(self::HOST_QIMA)) === self::HOST_QIMA) {
            return true;
        }

        if (preg_match(self::LOCAL_IP, $host)) {
            return true;
        }

        return false;
    }

    private static function isInWhiteList($host)
    {
        $arr = self::getEnvValue(self::ENV_PROXY_NO_PROXY_HOSTS);
        if (is_array($arr) && in_array($host, $arr)) {
            return true;
        }

        return false;
    }

}