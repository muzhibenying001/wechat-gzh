<?php 

$ch = curl_init('http://wechat.luoying.club/wx.xml');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

 $ip_info = curl_exec($ch);

echo "<pre>";
 print_r($ip_info);