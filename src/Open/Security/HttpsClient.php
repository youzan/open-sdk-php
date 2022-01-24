<?php


namespace Youzan\Open\Security;

use Youzan\Open\Http;

class HttpsClient
{


    public static function postJson($requestURL,$param) {
        return Http::post($requestURL,$param);
    }

    public static function directJostJson($requestURL, $param) {

        $response = '';
        $hasConn = False;

        for($retry=0;$retry<2 && !$hasConn;$retry++){
            $ch = curl_init($requestURL);
            curl_setopt($ch, CURLOPT_POST, True);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 10000);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 10000);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);  // True to return the transfer as a string of the return value
//            curl_setopt($ch, CURLOPT_CAINFO, self::$CAPath); // CA certificate
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, True);  // True verify the peer's certificate
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // True verify the peer's certificate
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // True verify the peer's certificate
            curl_setopt($ch, CURLOPT_HTTPHEADER, self::httpJsonHeader());
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
            $response = curl_exec($ch);

            // Get the error number for the last cURL operation, if no error occurs then return 0.
            if(curl_errno($ch)){
                $rootCause = curl_error($ch);
                // TODO throw exception
            }

            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($response_code != 200){
                $rootCause = "Wrong http reponse code:".$response_code;
                // TODO throw exception
            }

            $hasConn = True;
        }

        return $response;
    }

    public static function httpJsonHeader() {
        $header = array(
            "Accept:application/json",
            "Content-Type:application/json;charset=UTF-8",
            "Connection:keep-alive"
        );
        return $header;
    }


}