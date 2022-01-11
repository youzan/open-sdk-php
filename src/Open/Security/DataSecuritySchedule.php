<?php


namespace Youzan\Open\Security;
use \Youzan\Open\Security\SecurityDataTimer;

class DataSecuritySchedule
{


    public static function refreshCache($httpSecretCache)
    {
        try{
            if(null != $httpSecretCache && SecurityDataTimer::compareAndSwapRefesh()) {
                $httpSecretCache->refreshAll();
            }
        }catch (\Throwable $e) {
            // 日志打印
            echo "系统异常".$e->getMessage();
            var_dump($e->getMessage());
        }


    }


}