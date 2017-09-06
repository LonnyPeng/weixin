<?php

$handle = fopen('log.txt', 'a');

$str = '';

$str .= date("Y-m-d H:i:s") . ": ";

$str .= var_export($_SERVER, true);

$str .= "\n\r";

fwrite($handle, $str);

fclose($handle);