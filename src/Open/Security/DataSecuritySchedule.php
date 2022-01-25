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
        }catch (\Exception $e) {
            throw $e;
        }


    }


}