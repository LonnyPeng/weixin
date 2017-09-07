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
	//获取accessToken
	$urlInfo = array(
		'url' => "https://api.weixin.qq.com/cgi-bin/token",
		'params' => array(
			'grant_type' => "client_credential",
			'appid' => APPID,
			'secret' => APPSECRET,
		),
	);
	$accessTokenInfo = curl($urlInfo);
	$accessTokenInfo = json_decode($accessTokenInfo, true);
	$accessToken = $accessTokenInfo['access_token'];
	
	//自定义菜单
	$data = array(
		'button' => array(
			array(
				'type' => 'click',
				'name' => '今日歌曲',
			),
		),
	);
	$urlInfo = array(
		'url' => "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $accessToken,
		'params' => $data,
	);
	curl($urlInfo, 'POST');
}