<?php 

$ch = curl_init('http://wechat.luoying.club/logs/error.log');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

 $ip_info = curl_exec($ch);

 print_r($ip_info);

