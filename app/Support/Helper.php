<?php

if (!function_exists('active')) {
    /**
     * Active url
     *
     * @param  array|string $path
     * @param  string $class
     * @return string
     */
    function active($path, $class = "active"): string
    {
        return call_user_func_array('Request::is', (array) $path) ? $class : "";
    }
}
