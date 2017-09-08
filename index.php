<?php

define("TOKEN", "weixin");
define("APPID", "wx1df1a198b53b46b5");
define("APPSECRET", "6cf357e7fa19f111721f555b272d3ef0");

include_once __DIR__ . "/Fun.php";

if (isset($_GET["echostr"]) && isset($_GET["signature"]) && isset($_GET["timestamp"]) && isset($_GET["nonce"])) {
	$signature = $_GET["signature"];
	$timestamp = $_GET["timestamp"];
	$nonce = $_GET["nonce"];

	$token = TOKEN;
	
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	
	$tmpStr = implode($tmpArr);
	$tmpStr = sha1($tmpStr);

	if($tmpStr == $signature) {
	    echo $_GET["echostr"];
	} else {
	    echo 'error';
	}
} else {
	$postStr = file_get_contents('php://input');
	$postObj = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
	$fromUsername = $postObj->FromUserName;
	$toUsername = $postObj->ToUserName;
	$keyword = trim($postObj->Content);

	$langArr = array(
	    "sq", "ar", "am", "az", "ga", "et", "eu", "be", "bg", "is", "pl", "bs", "fa", "af", "da", "de", "ru", "fr", "tl", "fi", 
	    "fy", "km", "ka", "gu", "kk", "ht", "ko", "ha", "nl", "ky", "gl", "ca", "cs", "kn", "co", "hr", "ku", "la", "lv", "lo", 
	    "lt", "lb", "ro", "mg", "mt", "mr", "ml", "ms", "mk", "mi", "mn", "bn", "my", "hmn", "xh", "zu", "ne", "no", "pa", "pt", 
	    "ps", "ny", "ja", "sv", "sm", "sr", "st", "si", "eo", "sk", "sl", "sw", "gd", "ceb", "so", "tg", "te", "ta", "th", "tr", 
	    "cy", "ur", "uk", "uz", "es", "iw", "el", "haw", "sd", "hu", "sn", "hy", "ig", "it", "yi", "hi", "su", "id", "jw", "en", 
	    "yo", "vi", "zh-TW", "zh-CN", 
	);

	$result = explode("@", $keyword);
	if (count($result) < 2) {
		$result = "你好，我的世界。@en";
	} elseif (!in_array($result[1], $langArr)) {
		$result = sprintf("你好，我的世界。@[%s]", implode(", ", $langArr));
	} else {
		$result = translateGoogleApi(array('tl' => $result[1], 'text' => $result[0]));
	}

	$textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>0</FuncFlag>
</xml>";
	
	$contentStr = sprintf($textTpl, $fromUsername, $toUsername, time(), 'text', $result);

	echo $contentStr;

	$handle = fopen('log.txt', 'a');
	$postStr = date("Y-m-d H:i:s") . ": " . $postStr . "\n\r";
	fwrite($handle, $postStr);
	fclose($handle);
}