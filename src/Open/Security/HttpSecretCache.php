<?php
namespace Youzan\Open\Security;
include "HttpSecretInvoker.php";
include "SecretKeyQueryRequest.php";
include "ClientEncryptConfig.php";
include "SecretData.php";

use Youzan\Open\Security\HttpSecretInvoker;
use LitJson;

class HttpSecretCache
{
    private  $securityData;
    private  $secretInvoker;
    private  $encryptType = 1;
    public $secretCacheMap;


    public function __construct($securityData,$env)
    {
        $this->secretCacheMap = array();
        $this->securityData = $securityData;
        $this->secretInvoker = new HttpSecretInvoker($env);
        $this->init();
    }

    private function init() {
        $this->doInit();
    }

    private function doInit() {
        $secretCacheInitMap = array();
        $jsonMapper = new DataSecurityJsonMapper();
        $clientConfig = $this->querySecret(null,$jsonMapper);
        if($clientConfig == null) {
            return;
        }
        $secretKeyInfoList = $clientConfig->getSecretKeyInfoList();
        if(empty($secretKeyInfoList)) {
            return;
        }
        for($i=0;$i<count($secretKeyInfoList);$i++) {
            $secretData = $jsonMapper->map($secretKeyInfoList[$i],new SecretData());
            if(array_key_exists($secretData->getKdtId(),$secretCacheInitMap)) {
                array_push($secretCacheInitMap[$secretData->getKdtId()],$secretData);
            }else {
                $kdtArray = array($secretData);
                $secretCacheInitMap[$secretData->getKdtId()] = $kdtArray;
            }
        }
        if(count($secretCacheInitMap)>0) {
            $this->secretCacheMap = $secretCacheInitMap;
        }
        $this->encryptType = $clientConfig->getEncryptType();


    }
    private function querySecret($kdtId,$jsonMapper) {
        $timestamp = date('Y-m-d H:i:s');
        $secretQueryRequest = new SecretKeyQueryRequest($this->securityData->getClientId(),$kdtId,$timestamp,$this->securityData->getClientSecret());
//        $secretConfigResult = $this->secretInvoker->invoke(json_encode($secretQueryRequest));
        $secretConfigResult = $this->secretInvoker->invoke($secretQueryRequest);
        if($secretConfigResult == null || $secretConfigResult->getData() == null) {
            return null;
        }
        return $jsonMapper->map($secretConfigResult->getData(),new ClientEncryptConfig());
    }

    public function refreshAll() {
        try{
            $this->doInit();
        }catch (\Exception $e) {
            throw $e;
        }
    }

    private function refresh($kdtId) {
        $jsonMapper = new DataSecurityJsonMapper();
        $clientConfig = $this->querySecret($kdtId,$jsonMapper);
        $secretKeyInfoList = $clientConfig->getSecretKeyInfoList();
        if(empty($secretKeyInfoList)) {
            return;
        }
        $kdtSecretList = array();
        for($i=0;$i<count($secretKeyInfoList);$i++) {
            $secretData = $jsonMapper->map($secretKeyInfoList[$i],new SecretData());
            array_push($kdtSecretList,$secretData);
        }
        $this->secretCacheMap[$kdtId] = $kdtSecretList;
    }

    /**
     * @return int
     */
    public function getEncryptType()
    {
        return $this->encryptType;
    }


   public function getNewestAndRefresh($kdtId) {
        $secretData = $this->getNewest($kdtId);
        if(null == $secretData) {
           $this->refresh($kdtId);
            $secretData = $this->getNewest($kdtId);
        }
        return $secretData;
    }

   public function getAndRefresh($kdtId,$version) {
        $secretData = $this->getSecretForVersion($kdtId,$version);
        if(null == $secretData) {
            $this->refresh($kdtId);
            $secretData = $this->getSecretForVersion($kdtId,$version);;
        }
        return $secretData;
    }

    public function getSecretForVersion($kdtId,$version) {
        $secretList = $this->secretCacheMap[$kdtId];
        if(empty($secretList)) {
            return null;
        }
        foreach ($secretList as $value) {
            if($value->getVersion() == $version) {
                return $value;
            }
        }
        return null;
    }


    private function getNewest($kdtId) {
        if(!array_key_exists($kdtId,$this->secretCacheMap)) {
            return null;
        }
       $secretList = $this->secretCacheMap[$kdtId];
       if(empty($secretList)) {
           return null;
       }
       $maxVersionSecret = null;
       foreach ($secretList as $value) {
           if($maxVersionSecret == null || $value->getVersion() > $maxVersionSecret->getVersion()) {
               $maxVersionSecret = $value;
           }
       }
       return $maxVersionSecret;
    }

}