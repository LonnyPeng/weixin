<?php

$handle = fopen('log.txt', 'a');

$str = '';

$str .= date("Y-m-d H:i:s") . ": ";

$info = array(
	'server' => $_SERVER,
	'post' => $GLOBALS["HTTP_RAW_POST_DATA"],
);
$str .= var_export($info, true);

$str .= "\n\r";

fwrite($handle, $str);

fclose($handle);