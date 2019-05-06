<?php

require_once './vendor/autoload.php';
// 未使用 composer 进行包管理的引入方式
// require_once '/path/to/youzanyun-open-sdk/open-sdk/vendor/autoload.php';

use Youzan\Open\Helper\CryptoHelper;


// 接收到的订购消息密文
$message = 'zanO3UZzEA5Fm6qKsCq6rJ70VoKdiqXDfgdqGvzOfIZ%2FVNB%2FUHuG7%2F6TxdL6NQIVkO8CFm20whTivoPj4%2B6nXLAoe9J%2BZc42nRAbkTg5GruMl8RohxSDS0%2F99FwGtLW9TmMbxs554ZWVaRaiB5KaHF%2FNTzuLHyEtrLB2xu8Y%2BAnMN%2FVVVO9PPgO8o1BSAuvJdNXa1%2ButpG%2BRSSSMbxXrvCkRC34X7kCK1z5Xg51r%2Fym8nxrrSFn2c4R3rMRxKQAMmzRfGBkcQ9XayS31oT5DNy0h5pWzP8W5pe9naUguCiPIIAqGmBo8etlIn1Y1FHAU';

// 应用的clientSecret
$clientSecret = 'def00000b1228e0f6ba8ef96be9';

$res = CryptoHelper::decrypt($message, $clientSecret);
var_dump($res);
// {"appId":110,"buyerId":123456,"buyerPhone":"13800138000","env":"PROD","kdtId":160,"orderNo":"E201905060000001","payTime":1557138032000,"price":42,"skuIntervalText":"7","skuVersionText":"试用版","status":20,"type":"APP_SUBSCRIBE"}
