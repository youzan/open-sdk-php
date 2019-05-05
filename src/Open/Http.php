<?php

namespace Youzan\Open;

use GuzzleHttp\Client;
use Youzan\Open\Config\EcommerceConfig;


class Http
{

    public static function post($url, $params = [], $files = [])
    {
        $client = new Client();

        $response = $client->request(
            'POST',
            $url,
            self::buildOptional($params, $files)
        );

        return $response->getBody()->getContents();
    }


    private static function buildOptional($params = [], $files = [])
    {
        $ret = [
            'proxy' => EcommerceConfig::getHttpProxy(),
            'headers' => EcommerceConfig::getHttpHeaders(),
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