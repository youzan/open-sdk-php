<?php


namespace Youzan\Open\Security;


abstract class EnvUrl
{
    const inti = "api/auth_exempt/youzan.cloud.client.query.encryptconfig/1.0.0";

    public abstract function getUrl();

    public function getInitUrl() {
        return $this->getUrl() . self::inti;
    }
}