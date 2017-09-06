<?php

$str = file_get_contents('php://input');
saveLog($str);

function saveLog($str = '') {
	$handle = fopen('log.txt', 'a');

	$str .= date("Y-m-d H:i:s") . ": ";

	fwrite($handle, var_export($str, true) . "\n\r");

	fclose($handle);
}