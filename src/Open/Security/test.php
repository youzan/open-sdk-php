<?php

use Workerman\Worker;
use Youzan\Open\Security\SecretClient;

header("Content-type:text/html;charset=utf-8");
require_once '../../../vendor/autoload.php';

include "SecretClient.php";
//$secretClient = new SecretClient("ff339e73f9ee5d5cb6","11661eb9e3b2287a90b16385e00d1ee9");
//$kdtId = 58832146;
$secretClient = SecretClient::getnstance("68ffaf8440a4b7940c","b0030740948e368c4ca21ed5d0b01725");
$kdtId = 60102958;
$encryptRel = $secretClient->singleEncrypt($kdtId,"测试");
$batchEncryptArray = array("胖晨","18736955555","杭州市西湖区华泰科技园5号楼2楼217");
$batchEncryptArrayRel = $secretClient->batchEncrypt($kdtId,$batchEncryptArray);

$decryptRel = $secretClient->singleDecrypt($kdtId,$encryptRel);
$batchDecryptArrayRel = $secretClient->batchDecrypt($kdtId,array_values($batchEncryptArrayRel));

$encryptIsEncrypt = $secretClient->singleIsEncrypt($encryptRel);
$decryptIsEncrypt = $secretClient->singleIsEncrypt($decryptRel);

$batchEncryptIsEncrypt = $secretClient->batchIsEncrypt($batchEncryptArray);
$batchDecryptIsEncrypt = $secretClient->batchIsEncrypt(array_values($batchEncryptArrayRel));

// <!-- 脱敏 -- !>
$address = "杭州市西湖区华泰科技园7号楼2楼217";
$name = "李开开";
$email = "1196501458@qq.com";
$companyName = "杭州有赞科技股份有限公司";
$idCard = "41162719940313441X";
$mobile = "18736955651";

$markAddress = $secretClient->singleDecryptMask($kdtId,$address,'ADDRESS');
$markName = $secretClient->singleDecryptMask($kdtId,$name,'NAME');
$markEmail = $secretClient->singleDecryptMask($kdtId,$email,'EMAIL');
$markCompanyName = $secretClient->singleDecryptMask($kdtId,$companyName,'COMPANY_NAME');
$markIdCard = $secretClient->singleDecryptMask($kdtId,$idCard,'ID_CARD');
$markMobile = $secretClient->singleDecryptMask($kdtId,$mobile,'MOBILE');


$searchAddress = "西湖区华泰";
$searchName = "开";
$searchEmail = "1458@qq.";
$searchCompanyName = "有赞科技";
$searchIdCard = "13441X";
$searchMobile = "3695";

$addressDigest = $secretClient->generateEncryptSearchDigest($kdtId,$address);
$nameDigest = $secretClient->generateEncryptSearchDigest($kdtId,$name);
$emailDigest = $secretClient->generateEncryptSearchDigest($kdtId,$email);
$companyNameDigest = $secretClient->generateEncryptSearchDigest($kdtId,$companyName);
$idCardDigest = $secretClient->generateEncryptSearchDigest($kdtId,$idCard);
$mobileDigest = $secretClient->generateEncryptSearchDigest($kdtId,$mobile);

$searchAddressDigest = $secretClient->generateEncryptSearchDigest($kdtId,$searchAddress);
$searchNameDigest = $secretClient->generateEncryptSearchDigest($kdtId,$searchName);
$searchEmailDigest = $secretClient->generateEncryptSearchDigest($kdtId,$searchEmail);
$searchCompanyNameDigest = $secretClient->generateEncryptSearchDigest($kdtId,$searchCompanyName);
$searchIdCardDigest = $secretClient->generateEncryptSearchDigest($kdtId,$searchIdCard);
$searchMobileDigest = $secretClient->generateEncryptSearchDigest($kdtId,$searchMobile);


echo "<pre>";
echo '单项加密:'.$encryptRel;
echo PHP_EOL;
echo '单项解密:'.$decryptRel;
echo PHP_EOL;
echo '单项密文判断:'.$encryptRel .' => ';var_dump($encryptIsEncrypt);
echo PHP_EOL;
echo '单项密文判断:'.$decryptRel.' => ';var_dump($decryptIsEncrypt);
echo PHP_EOL;
echo '批量加密:';var_dump($batchEncryptArrayRel);
echo PHP_EOL;
echo '批量解密:';var_dump($batchDecryptArrayRel);
echo '批量密文判断:';var_dump($batchEncryptArray); echo ' => ';var_dump($batchEncryptIsEncrypt);
echo '批量密文判断:';var_dump(array_values($batchEncryptArrayRel)); echo ' => ';var_dump($batchDecryptIsEncrypt);
echo PHP_EOL;
echo '单项脱敏:' . 'ADDRESS =>'.$address.'=>'.$markAddress;
echo PHP_EOL;
echo '单项脱敏:' . 'NAME =>'.$name.'=>'.$markName;
echo PHP_EOL;
echo '单项脱敏:' . 'EMAIL =>'.$email.'=>'.$markEmail;
echo PHP_EOL;
echo '单项脱敏:' . 'COMPANY_NAME =>'.$companyName.'=>'.$markCompanyName;
echo PHP_EOL;
echo '单项脱敏:' . 'ID_CARD =>'.$idCard.'=>'.$markIdCard;
echo PHP_EOL;
echo '单项脱敏:' . 'MOBILE =>'.$mobile.'=>'.$markMobile;
echo PHP_EOL;
echo PHP_EOL;
echo "密文检索摘要地址:" . $address . '=>' . $addressDigest ;
echo PHP_EOL;
echo '是否包含:'; var_dump(count(explode($address,$addressDigest))>0);
echo PHP_EOL;

echo "密文检索摘要姓名:" . $name . '=>' . $nameDigest ;
echo PHP_EOL;
echo '是否包含:'; var_dump(count(explode($nameDigest,$searchNameDigest))>0);
echo PHP_EOL;
echo "密文检索摘要邮箱:" . $email . '=>' . $emailDigest ;
echo PHP_EOL;
echo '是否包含:'; var_dump(count(explode($emailDigest,$searchEmailDigest))>0);
echo PHP_EOL;
echo "密文检索摘要公司名称:" . $companyName . '=>' . $companyNameDigest ;
echo PHP_EOL;
echo '是否包含:'; var_dump(count(explode($companyNameDigest,$searchCompanyNameDigest))>0);
echo PHP_EOL;
echo "密文检索摘要身份证:" . $idCard . '=>' . $idCardDigest ;
echo PHP_EOL;
echo '是否包含:'; var_dump(count(explode($idCardDigest,$searchIdCardDigest))>0);
echo PHP_EOL;
echo "密文检索摘要手机号:" . $mobile . '=>' . $mobileDigest ;
echo PHP_EOL;
echo '是否包含:'; var_dump(count(explode($mobileDigest,$searchMobileDigest))>0);
echo PHP_EOL;




