<?php

$handle = fopen('log.txt', 'a');

$str = '';

$str .= date("Y-m-d H:i:s") . ": ";

$info = array(
	'server' => $_SERVER,
	'globals' => $GLOBALS["HTTP_RAW_POST_DATA"],
	'post' => $_POST,
);
$str .= var_export($info, true);

$str .= "\n\r";

fwrite($handle, $str);

fclose($handle);

// echo "weixin";