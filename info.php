<?php
header("Content-type: text/html; charset=utf8");
include './extend/myAutoUpload.php';
// include '/var/www/wechat/WechatPassive/WechatPassive.php';
// echo "a";die;
$wx_passive = new \WechatPassive\WechatPassive();
