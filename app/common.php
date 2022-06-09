<?php

if (!function_exists('success')) {
    function success(string $message = '', array $data = [], int $code = 200, array $header = [])
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => $code,
            'header' => $header,
        ];
    }
}

if (!function_exists('error')) {
    function error(string $message = '', array $data = [], int $code = 200, array $header = [])
    {
        return [
            'success' => false,
            'message' => $message,
            'data' => $data,
            'code' => $code,
            'header' => $header,
        ];
    }
}
