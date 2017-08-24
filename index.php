<?php

include_once __DIR__ . '/token.php';

if(checkSignature()){
    echo $_GET["echostr"];
}
else{
    echo 'error';
}