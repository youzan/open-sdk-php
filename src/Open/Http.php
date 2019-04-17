<?php

namespace Youzan\Open;

class Http
{
    private static $boundary = '';

    public static function post($url, $params, $files = array())
    {
        $headers = array();

        if (!$files) {
            $headers[] = "Content-Type: application/json";
            $postFields = $params;
        } else {
            $postFields = self::buildHttpQueryMulti($params, $files);
            $headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
        }

        return self::http($url, $postFields, $headers);
    }

    private static function http($url, $postFields = NULL, $headers = array())
    {
        $ci = curl_init();

        curl_setopt($ci, CURLOPT_USERAGENT, 'YZY-Open-Client 2.0.4 - PHP');
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
        curl_setopt($ci, CURLOPT_POST, TRUE);
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE);

        if (!is_null($postFields)) {
            curl_setopt($ci, CURLOPT_POSTFIELDS, json_encode($postFields, JSON_FORCE_OBJECT));
        }

        $response = curl_exec($ci);
        curl_close($ci);
        return $response;
    }

    private static function buildHttpQueryMulti($params, $files)
    {
        if (!$params) return '';

        self::$boundary = $boundary = uniqid('------------------');
        $MPBoundary = '--' . $boundary;
        $endMPBoundary = $MPBoundary . '--';
        $multipartBody = '';

        foreach ($params as $key => $value) {
            $multipartBody .= $MPBoundary . "\r\n";
            $multipartBody .= 'content-disposition: form-data; name="' . $key . "\"\r\n\r\n";
            $multipartBody .= $value . "\r\n";
        }

        foreach ($files as $key => $value) {
            if (!$value) {
                continue;
            }

            if (is_array($value)) {
                $url = $value['url'];
                if (isset($value['name'])) {
                    $filename = $value['name'];
                } else {
                    $parts = explode('?', basename($value['url']));
                    $filename = $parts[0];
                }
                $field = isset($value['field']) ? $value['field'] : $key;
            } else {
                $url = $value;
                $parts = explode('?', basename($url));
                $filename = $parts[0];
                $field = $key;
            }
            $content = file_get_contents($url);

            $multipartBody .= $MPBoundary . "\r\n";
            $multipartBody .= 'Content-Disposition: form-data; name="' . $field . '"; filename="' . $filename . '"' . "\r\n";
            $multipartBody .= "Content-Type: image/unknown\r\n\r\n";
            $multipartBody .= $content . "\r\n";
        }

        $multipartBody .= $endMPBoundary;
        return $multipartBody;
    }
}