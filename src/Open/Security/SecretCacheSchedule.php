<?php


namespace Youzan\Open\Security;
use Thread;

class SecretCacheSchedule extends \Thread
{
    private $httpSecretCache;

    /**
     * SecretCacheSchedule constructor.
     * @param $httpSecretCache
     */
    public function __construct($httpSecretCache)
    {
        $this->httpSecretCache = $httpSecretCache;
    }

    public function run()
    {
        while (true) {
            try{
                echo "====定时刷新开始====";
                $this->httpSecretCache->refreshAll();
                sleep(3000);
            }catch (\Exception $e) {
                // 日志打印
            }
        }
    }


}