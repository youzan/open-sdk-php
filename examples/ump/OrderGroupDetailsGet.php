<?php

require_once './vendor/autoload.php';

// 未使用 composer 进行包管理的引入方式
// require_once '/path/to/youzanyun-open-sdk/open-sdk/vendor/autoload.php';


$accessToken = 'YOUR_ACCESS_TOKEN';

$client = new \Youzan\Open\Client($accessToken);
$method = 'youzan.ump.groupon.ordergroupdetails.get';
$apiVersion = '1.0.0';

$params = [
    'tids' => ["E20190624152004073200001","E20190624152145048200006"]
];

$response = $client->post($method, $apiVersion, $params);
var_dump($response);
