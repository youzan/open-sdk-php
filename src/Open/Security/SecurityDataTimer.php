<?php


namespace Youzan\Open\Security;


class SecurityDataTimer
{
    private static $refreshTime;

    private static $schedulingPeriod = 10 * 60 * 1000;

    /**
     * SecurityDataTimer constructor.
     */
    public function __construct()
    {
        SecurityDataTimer::$refreshTime = time();
    }

    public static function compareAndSwapRefesh() {
        $now = time();
        if(($now - SecurityDataTimer::$refreshTime) >= SecurityDataTimer::$schedulingPeriod) {
            SecurityDataTimer::$refreshTime = $now;
            return true;
        }
        return false;
    }


}