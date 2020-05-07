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


## License

The MIT License. Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/youzanyun/open-sdk.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/youzanyun/open-sdk.svg?style=flat-square
[ico-travis]: https://api.travis-ci.org/youzan/open-sdk-php.svg

[link-packagist]: https://packagist.org/packages/youzanyun/open-sdk
[link-downloads]: https://packagist.org/packages/youzanyun/open-sdk
[link-travis]: https://travis-ci.org/youzan/open-sdk-php
