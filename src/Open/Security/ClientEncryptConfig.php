<?php


namespace Youzan\Open\Security;


class ClientEncryptConfig
{
    public $clientId;
    public $encryptType;
    public $whiteList;
    public $secretKeyInfoList;

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
    public function getEncryptType()
    {
        return $this->encryptType;
    }

    /**
     * @param mixed $encryptType
     */
    public function setEncryptType($encryptType)
    {
        $this->encryptType = $encryptType;
    }

    /**
     * @return mixed
     */
    public function getWhiteList()
    {
        return $this->whiteList;
    }

    /**
     * @param mixed $whiteList
     */
    public function setWhiteList($whiteList)
    {
        $this->whiteList = $whiteList;
    }

    /**
     * @return mixed
     */
    public function getSecretKeyInfoList()
    {
        return $this->secretKeyInfoList;
    }

    /**
     * @param mixed $secretKeyInfoList
     */
    public function setSecretKeyInfoList($secretKeyInfoList)
    {
        $this->secretKeyInfoList = $secretKeyInfoList;
    }




}