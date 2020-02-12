<?php

namespace Youzan\Open\Helper;

use Youzan\Open\Config\EcommerceConfig;

class EcommerceHelper
{

    public static function buildUrlAndHeaders($url)
    {
        $ret = [
            'url' => $url,
            'headers' => [],
        ];

        if (!self::isUseProxy()) {
            return $ret;
        }

        $urlArr = parse_url($url);

        $ret['url'] = self::buildUrl($urlArr);
        $ret['headers'] = self::getHttpHeaders($urlArr['host']);

        return $ret;
    }

    private static function buildUrl($urlArr)
    {
        $proxyHost = self::getEnvValue(EcommerceConfig::ENV_PROXY_HOST);

        if (strpos($proxyHost, 'http') !== 0) {
            $proxyHost = 'http://' . $proxyHost;
        }

        $url = $proxyHost;

        if (isset($urlArr['path'])) {
            $url .= $urlArr['path'];
        }

        if (isset($urlArr['query'])) {
            $url .= '?' . $urlArr['query'];
        }

        return $url;
    }

    private static function getHttpHeaders($hostname)
    {
        return [
            'Host' => $hostname,
            'Scheme' => EcommerceConfig::ENV_SCHEME,
            'yzc-token' => self::getEnvValue(EcommerceConfig::ENV_PROXY_TOKEN),
            'Yzc-Keepalive-Timeout' => self::getEnvValue(EcommerceConfig::ENV_PROXY_KEEPALIVE_TIMEOUT),
            'YZC-Keepalive-Poolsize' => self::getEnvValue(EcommerceConfig::ENV_PROXY_KEEPALIVE_POOL_SIZE),
        ];
    }


    private static function isUseProxy()
    {
        return (bool)self::getEnvValue(EcommerceConfig::ENV_PROXY_ENABLE);
    }


    private static function getEnvValue($key)
    {
        return getenv($key);
    }
}