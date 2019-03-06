<?php
/**
 * 被动公众平台接口
 */


$wx = new Wx();


class Wx {

	// 和公众平台约定好的token值
	// php7.1 
	private const TOKEN = 'luoying';
	// php7.0之前
	//const TOKEN = 'weixin';

	private $obj;

	// 消息的xml
	private $config = [];


	public function __construct(){

		if ($_GET["echostr"]) {  // 有此参数就执行验证
			echo $this->checkSignature();
		}else{
			/*// 获取原始数据
			$xml = file_get_contents('php://input');
			// 把xml转化为对象
			$obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
			echo $obj->Content."\n";
			var_dump($obj);*/
			$this->config = include 'config.php';
			// 接收消息方法和处理
			$this->acceptMesage();			
		}
	}


	/**
	 * 接受消息处理
	 */
	private function acceptMesage(){
		// 获取公众平台发送过来的xml数据
		$xml = file_get_contents('php://input');
		// 写接受日志
		$this->writeLog($xml);

		// 把xml转为object对象
		$this->obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

		// 消息类型
		$type = $this->obj->MsgType;

		$msg = '';

		// 动态方法 例 textFun
		$funName = $type.'Fun';

		// 消息管理方法处理
		//echo $msg = $this->$funName();
		// 一般在框架底层用到
		echo $msg = call_user_func([$this,$funName]);


		/*switch ($type) {
			case 'text': // 文本消息

			// 响应给公众号服务器
			echo $msg = $this->createText();
			
			break;

			case 'image':
			break;

			case 'voice':
				# code...
				break;
			
			}*/
			// 写发送日志
			if (!empty($msg)) {
				$this->writeLog($msg,1);
			}
		}

	/**
	 * 文本消息处理方法
	 */
	private function textFun(){
		$content = (string)$this->obj->Content;
		if (stristr($content,'图文')) {
			// 被动回复回文消息
			$picUrl1 ="http://n3wqdw.natappfree.cc/Love.png";//这是引用的图片地址				

			return sprintf($this->config['pic'],$this->obj->FromUserName,$this->obj->ToUserName,time(),$picUrl1);
		}
		// 响应给公众号服务器
		return $this->createText($content);
	}


	/**
	 * 生成文本消息的xml
	 */
	private function createText(string $content){
		return sprintf($this->config['text'],$this->obj->FromUserName,$this->obj->ToUserName,time(),"服务器：".$content);
	}






	/**
	 * 写日志
	 * @param  string      $xml  xml数据
	 * @param  int|integer $flag 0 接受  1 发送
	 */
	private function writeLog(string $xml,int $flag = 0){
		$title = $flag == 0 ? '接受' : '发送';
		$dtime = date('Y年m月d日 H:i:s');

		# 日志内容
		$log = $title."【{$dtime}】\n";
		$log .= "-------------------------------------------------------------------------\n";
		$log .= $xml."\n";
		$log .= "-------------------------------------------------------------------------\n";

		// 写日志 追加记录日志
		file_put_contents('wx.xml',$log,FILE_APPEND);
	}


	/**
	 * 初次接入验证
	 * @return string
	 */
	private function checkSignature(){
		# 公众平台传过来的数据
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$echostr = $_GET["echostr"];

		$tmpArr['token'] = self::TOKEN;
		$tmpArr['timestamp'] = $timestamp;
		$tmpArr['nonce'] = $nonce;
		# 进行字典
		sort($tmpArr, SORT_STRING);
		# 拼接成字符串
		$tmpStr = implode( $tmpArr );
		# 进行sha1加密
		$tmpStr = sha1( $tmpStr );

		# 验证通过
		if( $tmpStr == $signature ){
			return $echostr;
		}

		# 验证不通过
		return '';
	}
}
















