<?php 

/**
 * 被动公众平台接口
 */

 class Wechat{

 	#配置
 	private $config = [];
 	#记录用户发送过来的xml对象
 	private $obj = null;

 	public function __construct(){
 		#判断是否需要验证
 		if( isset($_GET['echostr']) ){
 			#调用验证函数
 			echo $this -> checkSignature();
 		}else{
 			#引入配置文件
 			$this -> config = include 'config.php';
 			#进行接收消息处理
 			$this -> acceptMessage();
 		}
 	}

 	#接收消息处理
 	private function acceptMessage(){
 		#获取公众号发送过来的xml数据
 		$xml = file_get_contents('php://input');
 		#将xml转成object对象
 		$this -> obj = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
 		#获取消息类型
 		$type = $this->obj->MsgType;
 		#获取消息内容 
 		$msg = '';
 		#将接收到的xml写入日志文档，方便后期查阅
 		$this -> writeLog($xml);
 		#设置动态调用类型相关方法名
 		$function_name = $type . 'Fun';
 		#调用方法
 		echo $msg = call_user_func([$this,$function_name]);
 		#将返回给用户的xml写入日志文档
 		if( !empty($msg) ) $this -> writeLog($msg,1); 		
 	}

 	#######################################################################
 	#文本消息处理方法
 	private function textFun(){

 		$content = (string) $this->obj->Content;
 		#设置接收到的相应的文本 进行相关处理
 		if( stristr($content,'图文-') ) return $this -> create('图文---');

 		#将内容响应回用户
 		return $this -> createText($content);

 	}

 	#生成回复给用户的文本xml
 	private function createText( string $content ){
 		return sprintf($this->config['text'],$this->obj->FromUserName,$this->obj->ToUserName,time(),"服务器：".$content);
 	}

 	#生成回复给用户的图文xml
 	private function createImage(string $media_id){
		return sprintf($this->config['image'],$this->obj->FromUserName,$this->obj->ToUserName,time(),$media_id);
	}

	#######################################################################
 	#事件消息处理方法
	public function eventFun(){
		#获取事件类型
		$event = strtolower((string) $this->obj->Event);
		#跟据不同的事件类型进行相关处理
		if( 'click001' == $eventKey ){
			$a = '';
		}
	}

 	#生成记录日志，参数：xml   flag:1为发送，0为接收
 	private function writeLog( string $xml , int $flag = 0 ){
 		#判断记录类型
 		$title = $flag == 0 ? '接受' : '发送';
 		#生成记录时间
 		$dtime = date('Y年m月d日 H:i:s');

		# 日志内容
		$log = $title."【{$dtime}】\n";
		$log .= "-------------------------------------------------------------------------\n";
		$log .= $xml."\n";
		$log .= "-------------------------------------------------------------------------\n";

		# 写入日志 追加记录日志
		file_put_contents('wx.xml',$log,FILE_APPEND);
 	}

 	#接入验证
 	private function checkSignature(){
 		#接收公共号传过来的数据
 		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce     = $_GET["nonce"];
		$echostr   = $_GET["echostr"];
		#临时存储数据待后续处理
		$tmp_array['token'] = this->config['token'];
		$tmp_array['timestamp'] = $timestamp;
		$tmp_array['nonce'] = $nonce;
		#进行字符排序
		sort($tmp_array,SORT_STRING);
		#将数组拼接字符串
		$str = implode($tmp_array);
		#加密字符串
		$str = sha1($str);

		#执行验证，判断验证结果,验证成功返回echostr 失败返回''
		if( $str == $signature ) return $echostr;

		return '';
 	}




 }