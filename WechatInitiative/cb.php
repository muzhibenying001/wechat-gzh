<?php

session_start();
// 回调处理得到openid

$code = $_GET['code'];

$appid = 'wxa52744db9a2af5a5';
$secret = 'd6cbf2f425d70c51d81ffca8cf324d77';

$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";

$arr = json_decode(http_request($url),true);

$access_token = $arr['access_token'];
$openid = $arr['openid'];

// 写入session
$_SESSION['openid'] = $openid;

// 拉取用户信息
$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
$arr = json_decode(http_request($url),true);
$_SESSION['userinfo'] = $arr;

// 登录成功后，页面跳转
header('location:jd.php');



	/**
	 * HTTP请求方法
	 * @param  string $url      [description]
	 * @param  string $data     [description]
	 * @param  string $filepath [description]
	 * @return [type]           [description]
	 */
	function http_request(string $url,$data = '',string $filepath = ''){
		// $filepath不为空表示有文件上传
		if(!empty($filepath)){
			$data['media'] = new CURLFile($filepath);
		}
		// 1、初始化 相当于打开了浏览器
		$ch = curl_init();
		// 2、相关的设置
		// 请求的URL地址设置
		curl_setopt($ch,CURLOPT_URL,$url);
		// 设置输出的信息不直接输出
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		// 取消https证书验证
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		// 设置请求的超时时间 单是秒
		curl_setopt($ch,CURLOPT_TIMEOUT,10);
		// 模拟一个浏览器型号
		curl_setopt($ch,CURLOPT_USERAGENT,'MSIE');

		// 表示有数据上传
		if (!empty($data)) {
			// 如果是一个字符串，表示是一个json
			if (is_string($data)) {
				// 如果json类型加一个头信息说明   // 设置头信息
				curl_setopt($ch,CURLOPT_HTTPHEADER,[
					'Content-Type: application/json;charset=utf-8'
				]);
			}
			// 告诉curl你使用了post请求
			curl_setopt($ch,CURLOPT_POST,1);
			// post的数据
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		}
		// 3、执行请求操作
		$data = curl_exec($ch);
		// 得到请求的错误码  0返回请求成功，大于0表示请求有异常
		$errno = curl_errno($ch);
		if (0 < $errno) {
			// 抛出自己的异常
			throw new Exception(curl_error($ch), 1000);
		}
		// 4、关闭资源
		curl_close($ch);

		// 返回数据
		return $data;
	}