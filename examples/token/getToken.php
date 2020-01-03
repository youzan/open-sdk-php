<?php

require_once './vendor/autoload.php';

// 未使用 composer 进行包管理的引入方式
// require_once '/path/to/youzanyun-open-sdk/open-sdk/vendor/autoload.php';



// 自用型应用 获取access_token
$clientId = "YOUR_CLIENT_ID";
$clientSecret = "YOUR_CLIENT_SECRET";

$type = 'silent';
$keys['kdt_id'] = 'YOUR_KDT_ID';
$keys['refresh'] = true;  //是否获取refresh_token(可通过refresh_token刷新token)

$accessToken = (new \Youzan\Open\Token($clientId, $clientSecret))->getToken($type, $keys);
var_dump($accessToken);



// 工具型应用 获取access_token
$clientId = "YOUR_CLIENT_ID";
$clientSecret = "YOUR_CLIENT_SECRET";

$type = 'authorization_code';
$keys['code'] = 'YOUR_CODE';

$accessToken = (new \Youzan\Open\Token($clientId, $clientSecret))->getToken($type, $keys);
var_dump($accessToken);



// 工具型应用及自用型应用 刷新access_token
$type = 'refresh_token';
$keys['refresh_token'] = 'YOUR_REFRESH_TOKEN';

$accessToken = (new \Youzan\Open\Token($clientId, $clientSecret))->getToken($type, $keys);
var_dump($accessToken);

