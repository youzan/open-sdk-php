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
            $length = mb_strlen($bankCard);
            if($length <= 6) {
                return $bankCard;
            }
            return MaskHandler::desensitize($bankCard, $length,2, $length - 5);
        }
        return $bankCard;
    }

    /**
     * @param $name 中文名
     * @return mixed|string
     */
    public static function maskName($name) {
        if(!empty($name)) {
            $length = iconv_strlen($name);
            if($length <= 1) {
                return $name;
            }
            return MaskHandler::desensitize($name,$length, 1, $length-1);
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
           $length = mb_strlen($email);
           if($length <= $lastAtIndex) {
               return $email;
           }
           return MaskHandler::desensitize($email,$length,2,  $lastAtIndex - 1);
        }
        return $email;
    }

    /**
     * @param $companyName 公司名称
     * @return mixed|string
     */
    public static function maskCompanyName($companyName) {
        if(!empty($companyName)) {
            $length = iconv_strlen($companyName);
            if($length < 6) {
                return $companyName;
            }
            $start = mb_substr($companyName,0,4);
            $end = mb_substr($companyName,$length - 2,2);
            $fill = "****";
            return $start.$fill.$end;
        }
        return $companyName;
    }

    /**
     * @param $idCard 身份证
     * @return mixed|string
     */
    public static function maskIdCard($idCard) {
        if(!empty($idCard)) {
            $length = mb_strlen($idCard);
            if($length < 6) {
                return $idCard;
            }
            return MaskHandler::desensitize($idCard,$length, 2,$length - 5);
        }
        return $idCard;
    }

    /**
     * @param $mobile 手机号
     * @return mixed|string
     */
    public static function maskMobile($mobile) {
        if(!empty($mobile)) {
            $length = mb_strlen($mobile);
            if($length < 7) {
                return $mobile;
            }
            return MaskHandler::desensitize($mobile, $length,3, $length - 5);
        }
        return $mobile;
    }




   private static function desensitize($string,$lenth, $start = 0, $end = 0){
       $replace = self::star;
       if(empty($string)) {
            return $string;
        }
        $str_arr = array();
        for($i=0; $i<$lenth; $i++) {
            if($i>=$start && $i<=$end){
                $str_arr[] = self::star;
            }
            else {
                $str_arr[] = mb_substr($string, $i, 1);
            }
        }
        return implode('',$str_arr);
    }


}