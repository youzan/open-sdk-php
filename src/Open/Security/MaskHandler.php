<?php


namespace Youzan\Open\Security;


class MaskHandler
{
    const star = "*";
    const at = "@";

    public static $const_address = "ADDRESS";
    public static $const_bank_card = "BANK_CARD";
    public static $const_name = "NAME";
    public static $const_email = "EMAIL";
    public static $const_company_name = "COMPANY_NAME";
    public static $const_id_card = "ID_CARD";
    public static $const_mobile = "MOBILE";
    

    /**
     * @param $address 地址
     * @return
     */
    public static function maskAddress($address) {
        if(!empty($address)) {
            return preg_replace('/\d/',self::star,$address);
        }
        return $address;
    }

    /**
     * @param $bankCard 银行卡
     * @return mixed|string
     */
    public static function maskBankCard($bankCard) {
        if(!empty($bankCard)) {
            return MaskHandler::desensitize($bankCard, 2, 4);
        }
        return $bankCard;
    }

    /**
     * @param $name 中文名
     * @return mixed|string
     */
    public static function maskName($name) {
        if(!empty($name)) {
            return MaskHandler::desensitize($name, 1, 2);
        }
        return $name;
    }

    /**
     * @param $email 邮箱
     * @return mixed|string
     */
    public static function maskEmail($email) {
        if(!empty($email)) {
           $lastAtIndex = strripos($email,self::at);
           if($lastAtIndex <= 1) {
               return $email;
           }
           return MaskHandler::desensitize($email, 2, strlen($email) - $lastAtIndex + 1);
        }
        return $email;
    }

    /**
     * @param $companyName 公司名称
     * @return mixed|string
     */
    public static function maskCompanyName($companyName) {
        if(!empty($companyName)) {
            if(iconv_strlen($companyName) < 6) {
                return $companyName;
            }
            return MaskHandler::desensitize($companyName, 4, (iconv_strlen($companyName) - 6));
        }
        return $companyName;
    }

    /**
     * @param $idCard 身份证
     * @return mixed|string
     */
    public static function maskIdCard($idCard) {
        if(!empty($idCard)) {
            if(strlen($idCard) < 6) {
                return $idCard;
            }
            return MaskHandler::desensitize($idCard, 4, (strlen($idCard)-6));
        }
        return $idCard;
    }

    /**
     * @param $mobile 手机号
     * @return mixed|string
     */
    public static function maskMobile($mobile) {
        if(!empty($mobile)) {
            if(strlen($mobile) < 7) {
                return $mobile;
            }
            return MaskHandler::desensitize($mobile, 3, strlen($mobile)-7);
        }
        return $mobile;
    }




   private static function desensitize($string, $start = 0, $length = 0){
       $replace = self::star;
       if(empty($string) || empty($length) || empty($replace)) {
            return $string;
        }
        $end = $start + $length;
        $strlen = mb_strlen($string);
        $str_arr = array();
        for($i=0; $i<$strlen; $i++) {
            if($i>=$start && $i<$end)
                $str_arr[] = self::star;
            else
                $str_arr[] = mb_substr($string, $i, 1);
        }
        return implode('',$str_arr);
    }


}