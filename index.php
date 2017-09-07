<?php

define("TOKEN", "weixin");

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

	$handle = fopen('log.txt', 'a');

	$postStr = date("Y-m-d H:i:s") . ": " . $postStr . "\n\r";

	fwrite($handle, $postStr);

	fclose($handle);
}