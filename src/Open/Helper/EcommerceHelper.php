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

        $proxyHost = self::getEnvValue(EcommerceConfig::ENV_PROXY_HOST);
        if (strpos($proxyHost, 'http') === 0) {
            $ret['url'] = sprintf("%s%s?%s",
                self::getEnvValue(EcommerceConfig::ENV_PROXY_HOST),
                $urlArr['path'],
                $urlArr['query']
            );
        } else {
            $ret['url'] = sprintf("http://%s%s?%s",
                self::getEnvValue(EcommerceConfig::ENV_PROXY_HOST),
                $urlArr['path'],
                $urlArr['query']
            );
        }

        $ret['headers'] = self::getHttpHeaders($urlArr['host']);

        return $ret;
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