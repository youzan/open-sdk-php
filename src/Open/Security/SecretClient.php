<?php


namespace Youzan\Open\Security;

include "EnvUrl.php";
include "EnvQaUrl.php";
include "SecurityData.php";
include "HttpSecretCache.php";
include "OpenClientSecretServer.php";

use Youzan\Open\Security\EnvUrl;
use Youzan\Open\Security\EnvQaUrl;
use Youzan\Open\Security\HttpSecretCache;
use Youzan\Open\Security\OpenClientSecretServer;
use Youzan\Open\Security\SecurityData;


class SecretClient
{

    private $openClientSecretServer;
    static $secretClient;

    public static function getInstance($clientId,$clientSecret) {
        if(null == SecretClient::$secretClient) {
            SecretClient::$secretClient = new SecretClient($clientId,$clientSecret,null);
        }
        return SecretClient::$secretClient;
    }
    public static function getQaInstance($clientId,$clientSecret) {
        if(null == SecretClient::$secretClient) {
            SecretClient::$secretClient = new SecretClient($clientId,$clientSecret,new EnvQaUrl());
        }
        return SecretClient::$secretClient;
    }

    public function __construct($clientId,$clientSecret,$env)
    {
        $this->construct($clientId,$clientSecret,$env);
    }


    private function construct($clientId,$clientSecret,$env) {
        if(null == $env) {
            $env = new EnvProdUrl();
        }
        $this->openClientSecretServer = new OpenClientSecretServer($clientId,$clientSecret,$env);
    }

    /**
     * 单项加密
     * @param $kdtId    必填 店铺id
     * @param $source   必填 加密内容
     */
    public function singleEncrypt($kdtId,$source) {
        return $this->openClientSecretServer->singleEncrypt($kdtId,$source);
    }

    /**
     * 批量加密接口
     * @param $kdtId
     * @param $sources
     * @return \Ds\Map|null key：原请求信息 value：加密后的信息
     */
    public function batchEncrypt($kdtId,$sources) {
        return $this->openClientSecretServer->batchEncrypt($kdtId,$sources);
    }

    /**
     * 单项解密
     * @param $kdtId    必填 店铺id
     * @param $source   必填 解密内容
     */
    public function singleDecrypt($kdtId,$source) {
        return $this->openClientSecretServer->singleDecrypt($kdtId,$source);
    }

    /**
     * 批量加密
     * @param $kdtId
     * @param $sources
     * @return \Ds\Map|null key：原请求信息 value：解密后的信息
     */
    public function batchDecrypt($kdtId,$sources) {
        return $this->openClientSecretServer->batchDecrypt($kdtId,$sources);
    }

    /**
     * 单项判断是否密文接口
     * @param $source
     * @return bool
     */
    public function singleIsEncrypt($source) {
        return $this->openClientSecretServer->singleIsEncrypt($source);
    }

    /**
     * 批量判断是否存在密文接口
     * @param $sources
     * @return bool 只要有一个非密文就响应false
     */
    public function batchIsEncrypt($sources) {
        return $this->openClientSecretServer->batchIsEncrypt($sources);
    }

    /**
     * 解密并脱敏
     * @param $kdtId    必填 店铺id
     * @param $source   必填 解密并脱敏内容 如密文则 -> 解密 -> 脱敏，明文直接进行脱敏
     * @param $maskType 脱敏类型
     */
    public function singleDecryptMask($kdtId,$source,$maskType) {
        return $this->openClientSecretServer->singleDecryptMask($kdtId,$source,$maskType);
    }

    /**
     * 批量解密脱敏接口
     * @param $kdtId
     * @param $sources
     * @param $maskType
     * @return array
     */
    public function batchDecryptMask($kdtId,$sources,$maskType) {
        return $this->openClientSecretServer->batchDecryptMask($kdtId,$sources,$maskType);
    }

    /**
     * 密文检索摘要生成
     * @param $kdtId
     * @param $source
     * @return mixed
     */
    public function generateEncryptSearchDigest($kdtId,$source) {
        return $this->openClientSecretServer->generateEncryptSearchDigest($kdtId,$source);
    }

}