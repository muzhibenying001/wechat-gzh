<?php
/**
 * @Author: Marte
 * @Date:   2019-03-02 20:10:52
 * @Last Modified by:   Marte
 * @Last Modified time: 2019-03-03 14:36:32
 */
$wx = new Wechat();
class Wechat{
    private $config = []; //可以返回的文档字符串
    private $obj; //接收过来的数据参数
    private const TOKEN = 'dabai';
    //首次登陆就需要验证所以放在构造方法中
    public function __construct(){
    if (isset($_GET["echostr"])) {  // 有此参数就执行验证
        echo $this->checkSignature();
    }else{
        // echo "未接收到参数";
        // 获取原始数据
        $xml = file_get_contents('php://input');
        // echo $xml;
        $this->config = include 'config.php';
        //对接收到数据进行方法和处理
        $this->acceptMesage();

    }
}

    private function acceptMesage(){
        //获取公众号平台发送过来的xml数据
        $xml = file_get_contents('php://input');
        //将接收过来的xml数据写入到日志中 writeLog($xml,0)
        $this->writeLog($xml);
        // var_dump($xml);
        //将接收过来的xml数据转换为对象存到obj中
        // $this->obj= simplexml_load_string($xml);

        $this->obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        //消息类型
        // echo ;
        // var_dump($this->obj->MsgType);
        $type = $this->obj->MsgType;
        // echo $this->obj->ToUserName;
        $msg = '';
        $c= $this->obj->Content;
        echo $c;
        switch ($type) {
            case 'text':
                echo $msg = $this->createText($c);
                // echo "text";
                break;
            case 'image':
                echo $msg = $this->createImage($c);
                // echo "image";
                break;
            case 'news':
                echo $msg = $this->createText($c);
                echo "image";
                break;
            default:
                # code...
                break;
        }
        if (!empty($msg)) {
            $this->writeLog($msg,1);
        }
    }

    // private function createText()
    // {
    //     return sprintf($this->config['text'],$this->obj->ToUserName,$this->obj->FromUserName,time(),"大白:".$this->obj->Content);
    // }
    private function createText( string $c){
        if (stristr($c,'图文')) {
            return $this->createNews($c);
        }else{
            return sprintf($this->config['text'],$this->obj->FromUserName,$this->obj->ToUserName,time(),"久叔：".$c);
        }

    }
    private function createNews( string $c){

            // 被动回复回文消息
            //xxxxx
            return sprintf($this->config['news'],$this->obj->FromUserName,$this->obj->ToUserName,time(),'久叔和你说','你将会是一个成功的人','http://www.baymax.work/dabai.jpg','http://bbs.baymax.work/');


    }

    /*
        @param string $xml xml数据
        int $flag 0 接收过来的数据  1 发送出去的数据
        int  $c_time 填写日志时间
     */
    private function writeLog(string $xml,int $flag = 0){
        $title = $flag ==0?'接收':'回复';
        $c_time = date('Y年m月d日 H:i:s');


        #日志内容
        $log = $title."【{$c_time}】\n";
        $log .="-------------------------------------------------------\n";
        $log .= $xml."\n";
        $log .= "----------------------------------------------------\n";
        //写日志
        file_put_contents('wx.xml',$log,FILE_APPEND);
    }
    //初次接入验证
    private function checkSignature()
    {
        //
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        //接收过来的echostr字符串验证
        $echostr = $_GET["echostr"];

        //系统token
        $tmpArr['token'] = self::TOKEN;
        $tmpArr['timestamp'] = $timestamp;
        $tmpArr['nonce'] = $nonce;
        //将三个字段进行字典排序
        sort($tmpArr, SORT_STRING);
        //拼接成新的字符串
        $tmpStr = implode( $tmpArr );
        //进行sha1加密
        $tmpStr = sha1( $tmpStr );
        //判断是否验证通过
        if( $tmpStr ==$signature ){
            return $echostr;
            }else{
            return '12312';
            }
    }
}
