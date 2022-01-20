# open-sdk-php

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]


YouzanYun SDK


## 安装

1. 使用 `composer`  

推荐使用该方式安装, 更优雅  

``` bash
$ composer require youzanyun/open-sdk
```

2. 不使用 `composer` 管理  

如果你的项目不使用`composer`管理, 可以直接下载 [Release包](https://github.com/youzan/open-sdk-php/releases) 并解压, 然后在项目中添加如下代码:  

``` php
require_once '/YOUR_SDK_PATH/youzanyun-open-sdk/open-sdk/vendor/autoload.php';
``` 

请注意, 需要下载的是最新的 `youzanyun-open-sdk.zip` 压缩包, 而不是 `Source code` 压缩包, `YOUR_SDK_PATH` 需更改为项目实际路径.   


## 使用

详情参考 [examples](examples)

### 1. 获取及刷新access_token

#### 工具型应用 获取access_token
``` php
require_once './vendor/autoload.php';

$clientId = "YOUR_CLIENT_ID";
$clientSecret = "YOUR_CLIENT_SECRET";
$resp = (new \Youzan\Open\Token($clientId, $clientSecret))->getToolAppToken('YOUR_CODE');
var_dump($resp);
```

#### 自用型应用 获取access_token
``` php
require_once './vendor/autoload.php';

$clientId = "YOUR_CLIENT_ID";
$clientSecret = "YOUR_CLIENT_SECRET";

// 1. 不获取refresh_token
$resp = (new \Youzan\Open\Token($clientId, $clientSecret))->getSelfAppToken('YOUR_KDT_ID');
var_dump($resp);

// 2. 获取refresh_token
$config['refresh'] = true;  //是否获取refresh_token(可通过refresh_token刷新token)
$resp = (new \Youzan\Open\Token($clientId, $clientSecret))->getSelfAppToken('YOUR_KDT_ID', $config);
var_dump($resp);
```

#### 工具型应用及自用型应用 刷新access_token
```php
require_once './vendor/autoload.php';

$clientId = "YOUR_CLIENT_ID";
$clientSecret = "YOUR_CLIENT_SECRET";
$resp = (new \Youzan\Open\Token($clientId, $clientSecret))->refreshToken('YOUR_REFRESH_TOKEN');
var_dump($resp);
```

### 2. 接口调用

#### Token方式
``` php
require_once './vendor/autoload.php';

$accessToken = 'YOUR_TOKEN';
$client = new \Youzan\Open\Client($accessToken);

$method = 'youzan.item.get';
$apiVersion = '3.0.0';

$params = [
    'alias' => 'fa8989ad342k',
];

$response = $client->post($method, $apiVersion, $params);
var_dump($response);
```

#### 免鉴权方式 (仅支持免鉴权接口)
``` php
require_once './vendor/autoload.php';

$client = new \Youzan\Open\Client();

$method = 'youzan.item.get';
$apiVersion = '3.0.0';

$params = [
    'alias' => 'fa8989ad342k',
];

$response = $client->post($method, $apiVersion, $params);
var_dump($response);
```

### 3. 加密消息解密

参考 [examples/crypto/decrypt.php](examples/crypto/decrypt.php)

### 4. 加解密调用
```php
require_once './vendor/autoload.php';
$secretClient = SecretClient::getnstance("clientId","clientSecret");

$kdtId = xxxx;
// 单项加密
$encryptRel = $secretClient->singleEncrypt($kdtId, "加密内容");
// 单项解密
$decryptRel = $secretClient->singleDecrypt($kdtId,"解密内容");
// 批量加密
$batchEncryptArray = array("xxx1","xxx2","xxx3");
$batchEncryptArrayRel = $secretClient->batchEncrypt($kdtId,$batchEncryptArray);
// 批量解密
$batchDecryptArrayRel = $secretClient->batchDecrypt($kdtId,array_values($batchEncryptArrayRel));
// 单项密文判断
$encryptIsEncrypt = $secretClient->singleIsEncrypt($encryptRel);
// 批量密文判断
$batchEncryptIsEncrypt = $secretClient->batchIsEncrypt($batchEncryptArray);
// 单项解密并脱敏 如果是密文则解密脱敏，明文直接脱敏
// 脱敏类型: ADDRESS 地址,BANK_CARD 银行卡,NAME 中文名,EMAIL 邮箱,COMPANY_NAME 企业名称,ID_CARD 身份证,MOBILE 手机号
$markAddress = $secretClient->singleDecryptMask($kdtId,"华泰创业园5号楼2楼217室",'ADDRESS');
// 密文检索摘要生成
$addressDigest = $secretClient->generateEncryptSearchDigest($kdtId,"华泰创业园5号楼2楼217室");

```
#### 4.1 脱敏规则:

|规则名称|脱敏规则|示例示例| 
|------|---------|--------------|
|地址脱敏|实现地址的信息脱敏：地址中数字使用\*代替 |杭州市西湖区蒋村街道\*\*号\*座\*楼\*室|
|银行卡脱敏|银行卡号的信息脱敏：前2，后4展示，其他使用\*代替|19******2135|
| 名字脱敏             |  中文名称的信息脱敏：展示第一个字，其他使用\*代替              | 张\*|
| 邮箱脱敏             |邮箱地址的信息脱敏：展示前2位，和邮箱后缀	            | zy******@163.com|
| 企业名称脱敏	             |企业名称的信息脱敏：展示前4个汉字，后2个汉字，中间固定4个\*            | 杭州有赞****公司|
| 身份证脱敏           | 身份证号信息脱敏：展示前2位，后4位	              | 13******0630|
| 手机脱敏             | 手机号的信息脱敏：展示前3，后4，其他使用\*代替	      | 135****3263|

脱敏类型: ADDRESS 地址,BANK_CARD 银行卡,NAME 中文名,EMAIL 邮箱,COMPANY_NAME 企业名称,ID_CARD 身份证,MOBILE 手机号

#### 4.2 密文检索:

<font color="red">密文摘要的长度是原明文的6倍左右，开发者需要合理评估数据库字段长度，避免字段超长</font>

开发者需要新增数据库字段存储密文摘要，在模糊查询是调用密文摘要接口生成查询信息对应密文摘要并去数据库中模糊查询；例如需要对数据库中mobile字段的密文进行检索，则需要新增mobile_encrypt_digest字段(数据库字段名自定义，此处仅举例),在手机号落库时调用密文检索摘要方法生成手机号(e.g. 18736956666)对应的密文检索摘要M1并落库到mobile_encrypt_digest中，模糊查询时调用密文检索摘要方法生成查询信息(e.g. 187)的密文检索摘要M2,并调用模糊查询sql进行模糊查询(e.g. selet * from mobile_info where mobile_encrypt_digest like '%M2%')


## License

The MIT License. Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/youzanyun/open-sdk.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/youzanyun/open-sdk.svg?style=flat-square
[ico-travis]: https://api.travis-ci.org/youzan/open-sdk-php.svg

[link-packagist]: https://packagist.org/packages/youzanyun/open-sdk
[link-downloads]: https://packagist.org/packages/youzanyun/open-sdk
[link-travis]: https://travis-ci.org/youzan/open-sdk-php
