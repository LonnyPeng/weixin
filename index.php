<?php

$str = file_get_contents('php://input');

$handle = fopen('log.txt', 'a');

$str = date("Y-m-d H:i:s") . ": " . $str . "\n\r";

fwrite($handle, $str);

fclose($handle);