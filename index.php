<?php

$str = file_get_contents('php://input');
log($str);

function log($str = '') {
	$handle = fopen('log.txt', 'a');

	$str .= date("Y-m-d H:i:s") . ": ";

	$str .= var_export($str, true);

	$str .= "\n\r";

	fwrite($handle, $str);

	fclose($handle);
}