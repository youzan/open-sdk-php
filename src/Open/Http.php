<?php

namespace Youzan\Open;

use GuzzleHttp\Client;
use Youzan\Open\Config\HttpConfig;
use Youzan\Open\Helper\EcommerceHelper;


class Http
{

    public static function post($url, $params = [], $files = [])
    {
        $client = new Client();

        $urlAndHeaders = EcommerceHelper::buildUrlAndHeaders($url);

        $response = $client->request(
            'POST',
            $urlAndHeaders['url'],
            self::buildOptional($params, $files, $urlAndHeaders['headers'])
        );

        return $response->getBody()->getContents();
    }


    private static function buildOptional($params = [], $files = [], $headers = [])
    {
        $ret = [
            'headers' => array_merge(HttpConfig::getHttpHeaders(), $headers),
        ];

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