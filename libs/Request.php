<?php

/**
 *
 * Request.php
 * User: kalvin
 * Date: 2018/2/5
 * Time: 下午4:49
 */

namespace libs;


class Request
{
    public function get($key, $default='')
    {
        return $_GET[$key] ?? $default;
    }

    public function post($key, $default='')
    {
        return $_POST[$key] ?? $default;
    }

    public function all($key, $default='')
    {
        return $_REQUEST[$key] ?? $default;
    }
}