<?php

$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
$content = $object->Content;

$textTpl = "<xml>  
<ToUserName><![CDATA[%s]]></ToUserName>  
<FromUserName><![CDATA[%s]]></FromUserName>  
<CreateTime>%s</CreateTime>  
<MsgType><![CDATA[text]]></MsgType>  
<Content><![CDATA[%s]]></Content>  
</xml>";  
  
$result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);

echo $result;