<?php 
/*
	被动公共平台接口配置

*/

return [
	#服务器配置令牌
	'token' => 'luoying',

	#返回给用户的xml
	'text' => <<<EOT
<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[text]]></MsgType>
	<Content><![CDATA[%s]]></Content>
</xml>
EOT
,
	'image' => <<<EOT
<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[image]]></MsgType>
	<Image>
		<MediaId><![CDATA[%s]]></MediaId>
	</Image>
</xml>
EOT
,

];