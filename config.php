<?php
/**
 * @Author: Marte
 * @Date:   2019-03-02 21:07:04
 * @Last Modified by:   Marte
 * @Last Modified time: 2019-03-03 08:43:47
 * %s表示占位符  tousername 发送给某人
 * fromusername  从谁那发出来
 */
return [
    //文本消息xml
    'text' =>"<xml>
            <ToUserName>
                <![CDATA[%s]]>
            </ToUserName>
            <FromUserName>
                <![CDATA[%s]]>
            </FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType>
                <![CDATA[text]]>
            </MsgType>
            <Content>
                <![CDATA[%s]]>
            </Content>
        </xml>",
        'image' =>"<xml>
            <ToUserName>
                <![CDATA[%s]]>
            </ToUserName>
            <FromUserName>
                <![CDATA[%s]]>
            </FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType>
                <![CDATA[text]]>
            </MsgType>
            <Content>
                <![CDATA[%s]]>
            </Content>
        </xml>",
        'news' =>"<xml>
              <ToUserName><![CDATA[%s]]></ToUserName>
              <FromUserName><![CDATA[%s]]></FromUserName>
              <CreateTime>%s</CreateTime>
              <MsgType><![CDATA[news]]></MsgType>
              <ArticleCount>1</ArticleCount>
              <Articles>
                <item>
                  <Title><![CDATA[%s]]></Title>
                  <Description><![CDATA[%s]]></Description>
                  <PicUrl><![CDATA[%s]]></PicUrl>
                  <Url><![CDATA[%s]]></Url>
                </item>
              </Articles>
            </xml>"
];