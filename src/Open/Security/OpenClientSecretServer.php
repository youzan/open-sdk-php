<?php


namespace Youzan\Open\Security;

include "SecretType.php";
include "Aes.php";
include 'MaskHandler.php';
require_once dirname(__FILE__) . '/salsa20/FieldElement.php';
require_once dirname(__FILE__) . '/salsa20/Salsa20.php';

use function Sodium\add;
use Youzan\Open\Security\Salsa20\FieldElement;
use Youzan\Open\Security\Salsa20\Salsa20;


class OpenClientSecretServer
{
    const separator = '$';

    private $secretCache;

    public function __construct($clientId,$clientSecret,$env)
    {
        $this->secretCache = new HttpSecretCache(new SecurityData($clientId,$clientSecret),$env);
    }


    /**
     * 单项加密
     * @param $kdtId    必填 店铺id
     * @param $source   必填 加密内容
     */
    public function singleEncrypt($kdtId,$source) {
        DataSecuritySchedule::refreshCache($this->secretCache);
        $kdtId = $this->convertKdtId($kdtId);
        $secretData = null;
        if(empty($source) || $this->singleIsEncrypt($source) || null == ($secretData = $this->secretCache->getNewestAndRefresh($kdtId))) {

            return $source;
        }
        $aes = new Aes();
        $encryptContext = $aes->encrypts($source,$secretData->getSecretKey());
        return $this->spliceEncryptContext($encryptContext,$secretData->getVersion());
    }

    /**
     * 批量加密
     * @param $kdtId
     * @param $sources
     * @return array
     */
    public function batchEncrypt($kdtId,$sources) {
        DataSecuritySchedule::refreshCache($this->secretCache);
        if(null == $sources) {
            return null;
        }
        $result = array();
        for ($i=0;$i<count($sources);$i++) {
            if(empty($sources[$i])) {
                continue;
            }
            $result[$sources[$i]] = $this->singleEncrypt($kdtId,$sources[$i]);
        }
        return $result;
    }

    /**
     * 单项解密
     * @param $kdtId    必填 店铺id
     * @param $source   必填 解密内容
     */
    public function singleDecrypt($kdtId,$source) {
        DataSecuritySchedule::refreshCache($this->secretCache);
        $kdtId = $this->convertKdtId($kdtId);
        $secretData = null;
        if(empty($source)
            || !$this->singleIsEncrypt($source)
            || (null == ($secretData=$this->secretCache->getAndRefresh($kdtId,$this->getEncryptVersion($source))))
        ){
            return $source;
        }
        $aes = new Aes();
        $result = $aes->decrypts($this->getOriginEncryptData($source),$secretData->getSecretKey());
        if($result == null) {
            throw new DataSecurityException("请使用正确的店铺进行解密操作",10050);
        }
    }

    /**
     * 批量解密
     * @param $kdtId
     * @param $sources
     * @return \Ds\Map|null
     */
    public function batchDecrypt($kdtId,$sources) {
        DataSecuritySchedule::refreshCache($this->secretCache);
        if(null == $sources) {
            return null;
        }
        $result = array();
        for ($i=0;$i<count($sources);$i++) {
            if(empty($sources[$i])) {
                continue;
            }
            $result[$sources[$i]]= $this->singleDecrypt($kdtId,$sources[$i]);
        }
        return $result;
    }

    /**
     * 单项判断是否加密
     * @param $source
     * @return bool
     */
    public function singleIsEncrypt($source): bool
    {
        if(empty($source) || strlen($source) < 4) {
            return false;
        }
        $stirs = explode(self::separator,$source);

        $encryptArray = array_filter($stirs);
        $length = count($encryptArray);
        if($length != 2) {
            return false;
        }
        return is_numeric($encryptArray[2]) && $this->is_base64($encryptArray[1]);
    }

    /**
     * 批量判断是否加密
     * @param $sources
     */
    public function batchIsEncrypt($sources): bool {
        if(empty($sources)) {
            return false;
        }
        for ($i=0;$i<count($sources);$i++) {
            if(!$this->singleIsEncrypt($sources[$i])){
                return false;
            }
        }
        return true;
    }

    /**
     * 解密并脱敏
     * @param $kdtId    必填 店铺id
     * @param $source   必填 解密并脱敏内容 如密文则 -> 解密 -> 脱敏，明文直接进行脱敏
     * @param $maskType 脱敏类型
     */
    public function singleDecryptMask($kdtId,$source,$maskType) {
        if($this->singleIsEncrypt($source)) {
            if(null == $kdtId) {
                throw new DataSecurityException(ErrorCode::$PARAM_ERROR,"脱敏内容是密文时店铺id为必填项");
            }
            $source = $this->singleDecrypt($kdtId,$source);
        }
        switch ($maskType) {
            case MaskHandler::$const_address:
                return MaskHandler::maskAddress($source);
            case MaskHandler::$const_bank_card:
                return MaskHandler::maskBankCard($source);
            case MaskHandler::$const_name:
                return MaskHandler::maskName($source);
            case MaskHandler::$const_email:
                return MaskHandler::maskEmail($source);
            case MaskHandler::$const_company_name:
                return MaskHandler::maskCompanyName($source);
            case MaskHandler::$const_id_card:
                return MaskHandler::maskIdCard($source);
            case MaskHandler::$const_mobile:
                return MaskHandler::maskMobile($source);
            default:
                throw new DataSecurityException(ErrorCode::$PARAM_ERROR,printf("param $maskType:%s is illegal",$maskType));
        }
    }

    public function batchDecryptMask($kdtId,$sources,$maskType) {
        $result = array();
        if(empty($sources) || empty($maskType)) {
            return $result;
        }
        for($i=0;$i<count($sources);$i++) {
            if(!empty($sources[$i])) {
                $result[$sources[$i]]=$this->singleDecryptMask($kdtId,$sources[$i],$maskType);
            }
        }
        return $result;
    }

    public function generateEncryptSearchDigest($kdtId,$source) {
        if(null == $source || empty($source)) {
            return $source;
        }
        if($this->singleIsEncrypt($source)) {
            if(null === $kdtId) {
                throw new DataSecurityException("source是密文时kddId 不能为空");
            }
            $source = $this->singleDecrypt($kdtId,$source);
        }
        $ret = "";
        $secretData = $this->secretCache->getSecretForVersion(0,1);
        if(null == $secretData) {
            return $source;
        }
        $key = $secretData->getSecretKey();
        $key = $this->fillSecretKey($key);
        $nonce = substr($key, 0, 24);
        $k = FieldElement::fromString($key) -> getArray();

        $nonceFieldElement =  FieldElement::fromString($nonce);
        $n = $nonceFieldElement -> getArray();

        $mblength = mb_strlen($source);

        for($i = 0; $i < $mblength; $i ++){
            $item = mb_substr($source, $i, 1);
            $len = strlen($item);
            if($len < 4){
                $padOffset_ = unpack("c*", $item);
                $padOffset = abs($padOffset_[1]) % 16;
                $padSize = 4 - $len;
                $item .= substr($key, $padOffset+4, $padSize);
            }
            $fieldElement = FieldElement::fromString($item);
            $m = $fieldElement->getArray();
            $ct = Salsa20::instance()->crypto_stream_xor($m, count($m), $n,$nonceFieldElement->slice(16), $k);
            $ret .= str_replace("==", "", $ct->toBase64());
        }
        return $ret;
    }

    function is_base64($str){
        return $str == base64_encode(base64_decode($str)) ? true : false;
    }

    function convertKdtId($kdtId) {
        if(SecretType::$app==$this->secretCache->getEncryptType()) {
            return 0;
        }
        return $kdtId;
    }


    private function spliceEncryptContext($encryptContext,$version) {
        return self::separator . $encryptContext . self::separator . $version . self::separator;
    }


    private function getEncryptVersion($source) {
        $stirs = explode(self::separator,$source);
        $stirs = array_filter($stirs);
        return $stirs[2];
    }

    private function getOriginEncryptData($source) {
        $stirs = explode(self::separator,$source);
        $stirs = array_filter($stirs);
        return $stirs[1];
    }

    private function fillSecretKey($key) {
        if(strlen($key) === 24) {
            $fill = "";
            for($i = 0;$i < strlen($key); $i=$i+3) {
                $fill = $fill . $key[$i];
            }
            $key = $key . strrev($fill);
        }else {
            $fill = substr($key,0, (32 - secret0.length()));
            $key = $key + strrev($fill);
        }
        return $key;
    }

}