<?php


namespace Youzan\Open\Security;


class Aes
{
    const IV = "0807060504030201"; //IV参数必须是16位。
    /**
     * 加密
     */
    public function encrypts($data,$key)
    {
        //php7.1 以上版本用法
        return base64_encode(openssl_encrypt($data, "AES-128-ECB", $key, OPENSSL_RAW_DATA));
    }
    /**
     * 解密
     */
    public function decrypts($data,$key)
    {
        //php7.1 以上版本用法
        return openssl_decrypt(base64_decode($data), "AES-128-ECB", $key, OPENSSL_RAW_DATA);
    }
}