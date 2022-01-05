<?php


namespace Youzan\Open\Security;


class SecretData
{
    public $clientId;
    public $kdtId;
    public $secretKey;
    public $version;

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
    public function setClientId($clientId)
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
    public function setKdtId($kdtId)
    {
        $this->kdtId = $kdtId;
    }

    /**
     * @return mixed
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param mixed $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }



}