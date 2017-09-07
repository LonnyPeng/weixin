<?php

define("TOKEN", "weixin");

if(checkSignature()) {
	if (isset($_GET["echostr"])) {
		echo $_GET["echostr"];
	} else {
		echo 'error';
	}
} else{
    $str = file_get_contents('php://input');

    $handle = fopen('log.txt', 'a');

    $str = date("Y-m-d H:i:s") . ": " . $str . "\n\r";

    fwrite($handle, $str);

    fclose($handle);
}

function checkSignature()
{
	if (!isset($_GET["signature"]) || !isset($_GET["timestamp"]) || !isset($_GET["nonce"])) {
		return false;
	}
    //从GET参数中读取三个字段的值
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
    //读取预定义的TOKEN
    $token = TOKEN;
    //对数组进行排序
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    //对三个字段进行sha1运算
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );
    //判断我方计算的结果是否和微信端计算的结果相符
    //这样利用只有微信端和我方了解的token作对比,验证访问是否来自微信官方.
    if( $tmpStr == $signature ){
        return true;
    }else{
        return false;
    }
}