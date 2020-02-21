<?php

require_once './vendor/autoload.php';

// 未使用 composer 进行包管理的引入方式
// require_once '/path/to/youzanyun-open-sdk/open-sdk/vendor/autoload.php';

// 自用型应用 获取access_token
$clientId = "YOUR_CLIENT_ID";
$clientSecret = "YOUR_CLIENT_SECRET";

// 不获取refresh_token
$resp = (new \Youzan\Open\Token($clientId, $clientSecret))->getSelfAppToken('YOUR_KDT_ID');
var_dump($resp);

// 获取refresh_token
$config['refresh'] = true;  //是否获取refresh_token(可通过refresh_token刷新token)
$resp = (new \Youzan\Open\Token($clientId, $clientSecret))->getSelfAppToken('YOUR_KDT_ID', $config);
var_dump($resp);


// 工具型应用 获取access_token(通过code换取)
$clientId = "YOUR_CLIENT_ID";
$clientSecret = "YOUR_CLIENT_SECRET";
$resp = (new \Youzan\Open\Token($clientId, $clientSecret))->getToolAppToken('YOUR_CODE');
var_dump($resp);


// 工具型应用及自用型应用 刷新access_token
$clientId = "YOUR_CLIENT_ID";
$clientSecret = "YOUR_CLIENT_SECRET";
$resp = (new \Youzan\Open\Token($clientId, $clientSecret))->refreshToken('YOUR_REFRESH_TOKEN');
var_dump($resp);

