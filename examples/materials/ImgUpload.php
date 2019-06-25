<?php


require_once './vendor/autoload.php';

// 未使用 composer 进行包管理的引入方式
// require_once '/path/to/youzanyun-open-sdk/open-sdk/vendor/autoload.php';


$accessToken = 'YOUR_ACCESS_TOKEN';

$client = new \Youzan\Open\Client($accessToken);
$method = 'youzan.materials.storage.platform.img.upload';
$apiVersion = '3.0.0';

$img = __DIR__ . '/pic.png';

$params = [

];

// 一次仅支持上传一张图片
$files = [
    'image' => $img
];

$response = $client->post($method, $apiVersion, $params, $files);
var_dump($response);
