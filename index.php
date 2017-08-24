<?php

include_once __DIR__ . '/Fun.php';

define("TOKEN","weixin");

$appid = "wx1df1a198b53b46b5";
$appsecret = "44e0a0be39e91ab2f3271836366749f2";

$urlInfo = array(
	'url' => 'https://api.weixin.qq.com/cgi-bin/token',
	'params' => array(
		'grant_type' => 'client_credential',
		'appid' => $appid,
		'secret' => $appsecret,
	),
);

$accessToken = curl($urlInfo);

$urlInfo = array(
	'url' => 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $accessToken,
	'params' => array(
		'button' => array(
			array(
				'type' => 'click',
				'name' => '首页',
				'url' => 'http://www.shuangwei89.top/weixin.php',
			),
		),
	),
);
$mune = curl($urlInfo, 'POST');

if(checkSignature()){
    echo $_GET["echostr"];
}
else{
    echo 'error';
}

function checkSignature()
{
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

