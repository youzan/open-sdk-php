<?php

require_once './vendor/autoload.php';

// 未使用 composer 进行包管理的引入方式
// require_once '/path/to/youzanyun-open-sdk/open-sdk/vendor/autoload.php';


$clientId = "YOUR_CLIENT_ID";
$clientSecret = "YOUR_CLIENT_SECRET";
$type = 'silent';
$keys['kdt_id'] = 110;

$accessToken = (new \Youzan\Open\Token($clientId, $clientSecret))->getToken($type, $keys);

var_dump($accessToken);

