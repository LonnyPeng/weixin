<?php

define("APPID", "wx1df1a198b53b46b5");
define("APPSECRET", "6cf357e7fa19f111721f555b272d3ef0");

include_once __DIR__ . "/Fun.php";

//获取 accessToken
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

