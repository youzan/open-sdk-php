<?php


namespace Youzan\Open\Security;


class SecretCacheSchedule
{
    private $httpSecretCache;

    /**
     * SecretCacheSchedule constructor.
     * @param $httpSecretCache
     */
    public function __construct($httpSecretCache)
    {
        $this->httpSecretCache = $httpSecretCache;
//        $this->run();
        $pid = \pcntl_fork();
        echo "=====pid：".$pid;
    }

    public function run()
    {
        try{
            echo "====定时刷新开始====";
            $this->httpSecretCache->refreshAll();
        }catch (\Throwable $e) {
            // 日志打印
            var_dump($e->getMessage());
        }


    }


}