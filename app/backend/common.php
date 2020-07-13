<?php

use think\Response;

if (!function_exists('resSuccess')) {
    function resSuccess(string $message = '', array $data = [], array $header = [])
    {
        $httpBody = ['success' => true, 'message' => $message, 'data' => $data];
        return jsonCross($httpBody, 200, $header);
    }
}
if (!function_exists('resError')) {
    function resError(string $message = '', array $data = [], array $header = [])
    {
        $httpBody = ['success' => false, 'message' => $message, 'data' => $data];
        return jsonCross($httpBody, 200, $header);
    }
}
