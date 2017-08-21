<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * Whether or not the POST request
 *
 * @return boolean
 */
function isPost()
{
    return isset($_SERVER['REQUEST_METHOD']) 
            && 'POST' === $_SERVER['REQUEST_METHOD'];
}

/**
 * Whether or not the AJAX request
 *
 * @return boolean
 */
function isAjax()
{
    return (
                isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
                && 'XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH']
            ) 
            || (
                isset($_REQUEST['X-Requested-With']) 
                && 'XMLHttpRequest' === $_REQUEST['X-Requested-With']
            );
}

/**
 * Whether or not the JSON
 *
 * @param string $string
 * @return boolean
 */
function isJson($string = "")
{
    if (!is_string($string)) {
        return false;
    }

    $result = json_decode($string);
    if (is_object($result)) {
        return true;
    } else {
        return false;
    }
}