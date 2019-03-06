<?php
/**
 * 网页授权生成url授权地址
 */

$appid = 'wxa52744db9a2af5a5';
// 进行urlencode转化  用在后台授权的域名对应地址
$redirect_uri = urlencode('http://deife9.natappfree.cc/WechatInitiative/cb.php');

$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=100#wechat_redirect";

// 跳转
header('location:'.$url);