<?php

require_once './vendor/autoload.php';

// 未使用 composer 进行包管理的引入方式
// require_once '/path/to/youzanyun-open-sdk/open-sdk/vendor/autoload.php';


$accessToken = 'YOUR_ACCESS_TOKEN';

$client = new \Youzan\Open\Client($accessToken);
$method = 'youzan.shop.get';
$apiVersion = '3.0.0';

$params = [];

$response = $client->post($method, $apiVersion, $params);
var_dump($response);

