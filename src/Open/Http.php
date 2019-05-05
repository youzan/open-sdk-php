<?php

namespace Youzan\Open;

use GuzzleHttp\Client;
use Youzan\Open\Config\EcommerceConfig;
use Youzan\Open\Config\HttpConfig;


class Http
{

    public static function post($url, $params = [], $files = [])
    {
        $client = new Client();

        $response = $client->request(
            'POST',
            $url,
            self::buildOptional($url, $params, $files)
        );

        return $response->getBody()->getContents();
    }


    private static function buildOptional($url, $params = [], $files = [])
    {
        $ret = [
            'headers' => HttpConfig::getHttpHeaders(),
        ];

        $isUseProxy = EcommerceConfig::isUseProxy(parse_url($url, PHP_URL_HOST));
        if ($isUseProxy) {
            $ret['headers'] = EcommerceConfig::getHttpHeaders();
            $ret['proxy'] = EcommerceConfig::getHttpProxy();
        }

        // 非上传文件请求
        if (empty($files)) {
            $ret['json'] = $params;
            return $ret;
        }

        // 上传文件请求
        foreach ($files as $key => $file) {
            if (file_exists($file)) {
                $ret['multipart'][] = [
                    'name' => $key,
                    'contents' => fopen($file, 'r'),
                ];
            }
        }

        return $ret;
    }
}