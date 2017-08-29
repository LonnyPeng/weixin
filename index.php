<?php

include_once __DIR__ . '/Fun.php';

define("TOKEN","weixin");
$appid = "wx1df1a198b53b46b5";
$appsecret = "44e0a0be39e91ab2f3271836366749f2";

/*-------------------------------------------------
|   index.php [ 微信公众平台接口 ]
+--------------------------------------------------
|   Author: LimYoonPer
+------------------------------------------------*/
$wechatObj = new wechat();
$wechatObj->responseMsg();

class wechat {
    public function responseMsg() {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"]; //获取POST数据
        //用SimpleXML解析POST过来的XML数据
        $postObj = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName; //获取发送方帐号（OpenID）
        $toUsername = $postObj->ToUserName; //获取接收方账号
        $keyword = trim($postObj->Content); //获取消息内容
        $time = time(); //获取当前时间戳
        //---------- 返 回 数 据 ---------- //
        //返回消息模板
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>0</FuncFlag>
</xml>";
        $msgType = "text"; //消息类型
        $contentStr = "Hello World"; //返回消息内容
        //格式化消息模板
        $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
        echo $resultStr; //输出结果
    }
}