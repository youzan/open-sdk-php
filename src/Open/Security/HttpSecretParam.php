<?php


namespace Youzan\Open\Security;


class HttpSecretParam
{
    private $clientId;
    private $kdtId;
    private $data;

    /**
     * HttpSecretParam constructor.
     * @param $clientId
     * @param $kdtId
     * @param $data
     */
    public function __construct($clientId, $kdtId, $data)
    {
        $this->clientId = $clientId;
        $this->kdtId = $kdtId;
        $this->data = $data;
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
    public function getKdtId()
    {
        return $this->kdtId;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }




}