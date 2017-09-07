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
	    echo $_GET["echostr"]
	} else {
	    echo 'error';
	}
} else {
	$str = file_get_contents('php://input');

	$handle = fopen('log.txt', 'a');

	$str = date("Y-m-d H:i:s") . ": " . $str . "\n\r";

	fwrite($handle, $str);

	fclose($handle);
}