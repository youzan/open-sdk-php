<?php


namespace Youzan\Open\Helper;


use Youzan\Open\Config\CryptoConfig;


class CryptoHelper
{
    /**
     * 消息解密(订购消息等)
     *
     * @param $messages         string  接收到的消息密文
     * @param $clientSecret     string  应用client_secret
     * @return string 解密后文本 (Json串)
     */
    public static function decrypt($messages, $clientSecret)
    {
        return openssl_decrypt(
            urldecode($messages),
            CryptoConfig::AES_128_CBC,
            substr($clientSecret, 0, CryptoConfig::KEY_LENGTH),
            CryptoConfig::OPTIONS,
            CryptoConfig::IV
        );
    }


    /**
     * 消息解密(订购消息等) 不对消息做decode
     *
     * @param $messages         string  接收到的消息密文
     * @param $clientSecret     string  应用client_secret
     * @return string 解密后文本 (Json串)
     */
    public static function decryptWithoutDecodeMessage($messages, $clientSecret)
    {
        return openssl_decrypt(
            $messages,
            CryptoConfig::AES_128_CBC,
            substr($clientSecret, 0, CryptoConfig::KEY_LENGTH),
            CryptoConfig::OPTIONS,
            CryptoConfig::IV
        );
    }
}

