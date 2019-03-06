<?php
/**
  * 公众号主动请求类
  */ 
namespace WechatInitiative;

$wx = new \WechatInitiative\WechatInitiative();
if($wx->createMenu()){
	echo '创建自定义菜单成功';
}else{
	echo '失败';
}





class WechatInitiative{
	#配置开发者账号
	const APPID = 'wxa52744db9a2af5a5';
	const SECRET = 'd6cbf2f425d70c51d81ffca8cf324d77';
	#存储接口配置文件
	private $config = [];

	public function __construct(){
		$this->config = include './config.php';
		# var_dump($this->config);
	}

	#素材上传
	public function upFile(string $type,string $filepath='',int $is_forever = 0){
		if ($is_forever == 0) { # 临时素材上传
			$url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=%s';
		}else{ # 永久
			$url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=%s&type=%s';
		}
		# 格式化url字符中
		$url = sprintf($url,$this->getAccessToken(),$type);
		# 发起POST请求
		$json = $this->http_request($url,[],$filepath);
		# 把JSON转为数组
		$arr = json_decode($json,true);
		return $arr['media_id'];
	}

	#创建自定义菜单
	public function createMenu(){
		# 创建自定义菜单URL地址
		$url = sprintf($this->config['create_menu_url'],$this->getAccessToken());
		$json = '{"button":[
					{"type":"click","name":"盼您一笑","key":"click001"},
					{"name":"菜单","sub_button":[
							{"type":"view","name":"搜索","url":"http://www.baidu.com/"},
							{"type":"view","name":"商城","url":"http://fqeuts.natappfree.cc/WechatInitiative/webAuth.php"},
						]}
				]}';
// var_dump($json);
		$errcode = json_decode($this->http_request($url,$json),true)['errcode'];
		// echo($errcode);
		return $errcode == 0 ?true:false;
	}

	# 删除自定义菜单
	public function deleteMenu(){
		$url = sprintf($this->config['delete_menu_url'],$this->getAccessToken());
		# 发起GET请求
		$errcode = json_decode($this->http_request($url),true)['errcode'];
		return $errcode == 0 ?true:false;
	}


	#获取access_token
	public function getAccessToken(){
		#判断缓存中是否有access_token
		// if (false != ($accessToken = $this->mem()->get(self::APPID))) {  
		// # 缓存中有数据
		// 	return $accessToken;
		// }

		# 访问的URL地址
		$url = sprintf($this->config['access_token_url'],self::APPID,self::SECRET);
		// echo "$url";
		# GET请求
		$arr = json_decode($this->http_request($url),true);
		// var_dump($arr);
		# 写入到memcache缓存中
		$this->mem()->set(self::APPID,$arr['access_token'],0,3600);
		# 返回access_token
		return $arr['access_token'];
	}

	#http请求方法
	private function http_request(string $url,$data = '',string $filepath = ''){
		# $filepath不为空表示有文件上传
		if(!empty($filepath)){
			$data['media'] = new CURLFile($filepath);
		}
		# 1、初始化 相当于打开了浏览器
		$ch = curl_init();
		# 2、相关的设置
		# 请求的URL地址设置
		curl_setopt($ch,CURLOPT_URL,$url);
		# 设置输出的信息不直接输出
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		# 取消https证书验证
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		# 设置请求的超时时间 单是秒
		curl_setopt($ch,CURLOPT_TIMEOUT,10);
		# 模拟一个浏览器型号
		curl_setopt($ch,CURLOPT_USERAGENT,'MSIE');

		# 表示有数据上传
		if (!empty($data)) {
			# 如果是一个字符串，表示是一个json
			if (is_string($data)) {
				# 如果json类型加一个头信息说明   # 设置头信息
				curl_setopt($ch,CURLOPT_HTTPHEADER,[
					'Content-Type: application/json;charset=utf-8'
				]);
			}
			# 告诉curl你使用了post请求
			curl_setopt($ch,CURLOPT_POST,1);
			# post的数据
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		}
		# 3、执行请求操作
		$data = curl_exec($ch);
		# 得到请求的错误码  0返回请求成功，大于0表示请求有异常
		$errno = curl_errno($ch);
		if (0 < $errno) {
			# 抛出自己的异常
			throw new Exception(curl_error($ch), 1000);
		}
		# 4、关闭资源
		curl_close($ch);

		# 返回数据
		return $data;
	}

	# 得到memcache对象
	private function mem(){
		$memcache = new \Memcache();
		$memcache->addServer('127.0.0.1',11211);
		return $memcache;
	}
}