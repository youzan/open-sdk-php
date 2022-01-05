<?php


namespace Youzan\Open\Security;

class SecretKeyQueryRequest
{
    /**
     * 应用id (区分环境)
     */
    public $client_id;
    /**
     * 店铺id
     */
    public $kdt_id;
    /**
     *
     * 签名
     */
    public $sign;
    /**
     * 时间搓 yyyy-MM-dd HH:mm:ss
     */
    public  $timestamp;

    /**
     * SecretKeyQueryRequest constructor.
     * @param $client_id
     * @param $kdt_id
     * @param $timestamp
     */
    public function __construct($client_id, $kdt_id, $timestamp,$client_secret)
    {
        $this->client_id = $client_id;
        $this->kdt_id = $kdt_id;
        $this->timestamp = $timestamp;
        $this->sign = $this->buildSign($client_secret);
    }

    private function buildSign($client_secret) {
        $arr = array();
        $arr["clientId"] = $this->client_id;
        if(null != $this->kdt_id) {
            $arr["kdtId"] = $this->kdt_id;
        }
        $arr["timestamp"] = $this->timestamp;
        ksort($arr);
        $calculatedSign = $client_secret;
        foreach ($arr as $key => $value) {
            $calculatedSign = $calculatedSign.$key.$value;
        }
        $calculatedSign = $calculatedSign.$client_secret;
        return md5($calculatedSign);
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getKdtId()
    {
        return $this->kdtId;
    }

    /**
     * @param mixed $kdtId
     */
    public function setKdtId($kdtId): void
    {
        $this->kdtId = $kdtId;
    }

    /**
     * @return string
     */
    public function getSign(): string
    {
        return $this->sign;
    }

    /**
     * @param string $sign
     */
    public function setSign(string $sign): void
    {
        $this->sign = $sign;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }


}