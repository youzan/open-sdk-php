# open-sdk-php

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]](link-travis)


YouzanYun SDK


## NOTICE

有赞开放平台升级为有赞云，开发者需要进行迁移工作，此为有赞云版本SDK。如果你之前未接入有赞云，建议直接使用本版本进行业务开发。如果已接入开放平台需迁移，可以使用兼容版本也可以直接使用本版本进行开发。

- [开放平台SDK 代号:carmen](../../tree/carmen)
- [兼容版本SDK 代号:compatible](../../tree/compatible)
- [有赞云版SDK 代号:bifrost](../../tree/bifrost)


## 安装

1. 使用 `Composer`
推荐使用该方式安装, 更优雅  

``` bash
$ composer require youzanyun/open-sdk
```

2. 不适应 `Composer` 管理  

如果你的项目不使用`Composer`管理, 可以直接下载[Release包](https://github.com/youzan/open-sdk-php/releases) 并解压, 然后在项目中添加如下代码:  
请注意, 需要下载的是最新的 `youzanyun-open-sdk.zip` 压缩包, 而不是 `Source code`  压缩包.  
`/path/to/` 更改为项目实际路径.   
``` php
require_once '/path/to/youzanyun-open-sdk/open-sdk/vendor/autoload.php';
``` 

## 使用

### 1. 获取 accessToken

#### 工具型应用
``` php
require_once './vendor/autoload.php';

$clientId = "fill your client_id";
$clientSecret = "fill your client_secret";
$redirectUrl = "fill your redirect_url";

$type = 'authorization_code';  //如要刷新access_token，type值为refresh_token
$keys['code'] = $_GET['code'];  //如要刷新access_token，这里为$keys['refresh_token']
$keys['redirect_uri'] = $redirect_url;

$accessToken = (new \Youzan\Open\Token($clientId, $clientSecret))->getToken($type, $keys);
var_dump($accessToken);
```

#### 自用型应用
``` php
require_once './vendor/autoload.php';

$clientId = "fill your client_id";
$clientSecret = "fill your client_secret";

$type = 'silent';
$keys['kdt_id'] = '160';

$accessToken = (new \Youzan\Open\Token($clientId, $clientSecret))->getToken($type, $keys);
var_dump($accessToken);
```

### 2. 接口调用

#### Token方式
``` php
require_once './vendor/autoload.php';

$accessToken = 'fill your token';
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

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Security

If you discover any security related issues, please using the issue tracker.


## License

The MIT License. Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/youzanyun/open-sdk.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/youzanyun/open-sdk.svg?style=flat-square
[ico-travis]: https://api.travis-ci.org/youzan/open-sdk-php.svg

[link-packagist]: https://packagist.org/packages/youzanyun/open-sdk
[link-downloads]: https://packagist.org/packages/youzanyun/open-sdk
[link-travis]: https://travis-ci.org/youzan/open-sdk-php
