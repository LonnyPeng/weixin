<?php

include_once __DIR__ . '/Fun.php';

define("TOKEN","weixin");
$appid = "wx1df1a198b53b46b5";
$appsecret = "44e0a0be39e91ab2f3271836366749f2";

$postStr = $GLOBALS["HTTP_RAW_POST_DATA"]; 
if (!emptyempty($postStr)){ 
    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); 
    $msgType = trim($postObj->MsgType);
    if ($msgType == 'text') {
       $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $str = sprintf($textTpl, $obj->FromUserName, $obj->ToUserName, time(), '123');

        echo $str;
    } else {
        echo "Error";
    }
} else { 
    echo "Error"; 
    exit; 
}