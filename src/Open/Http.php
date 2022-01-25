<?php

namespace Youzan\Open;

use Youzan\Open\Config\HttpConfig;
use Youzan\Open\Helper\EcommerceHelper;


class Http
{


    public static function post($url, $params = [], $files = [])
    {
        $client = new \GuzzleHttp\Client();

        $urlAndHeaders = EcommerceHelper::buildUrlAndHeaders($url);

        $response = $client->request(
            'POST',
            $urlAndHeaders['url'],
            self::buildOptional($params, $files, $urlAndHeaders['headers'])
        );
        print_r($response);
        return $response->getBody()->getContents();
    }


    private static function buildOptional($params = [], $files = [], $headers = [])
    {
        $ret = [
            'headers' => array_merge(HttpConfig::buildHttpHeaders(), $headers),
        ];

        // 非上传文件请求
        if (empty($files)) {
            $ret['headers']['Content-Type'] = 'application/json';
            $ret['body'] = self::buildBody($params);
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

    private static function buildBody($params)
    {
        if (empty($params)) {
            return '{}';
        }

        return json_encode($params);
    }
}