<?php


namespace Youzan\Open\Security;


class SecretParam
{
    private $clientId;
    private $clientSecret;
    private $kdtId;

    /**
     * SecretParam constructor.
     * @param $clientId
     * @param $clientSecret
     * @param $kdtId
     */
    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function buildKdt($kdtId) {
        $this->kdtId = $kdtId;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return mixed
     */
    public function getKdtId()
    {
        return $this->kdtId;
    }


}