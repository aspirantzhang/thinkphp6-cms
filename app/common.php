<?php

use think\Response;

function validateDateTime($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);

    return $d && $d->format($format) == $date;
}

function getSortParam($data, $allowSort)
{
    $sort['name'] = 'id';
    $sort['order'] = 'desc';

    if (isset($data['sort'])) {
        // check if exist in allowed list
        $sort['name'] = in_array($data['sort'], $allowSort) ? $data['sort'] : 'id';
    }
    if (isset($data['order'])) {
        $sort['order'] = ('asc' == $data['order']) ? 'asc' : 'desc';
    }

    return $sort;
}

function getSearchParam($data, $allowSearch)
{
    return array_intersect_key($data, array_flip($allowSearch));
}

function getPerPageParam($data)
{
    $perPage = 10;
    if (isset($data['per_page'])) {
        $perPage = $data['per_page'];
    }

    return $perPage;
}

function msg($errorCode, $message = null)
{
    switch ($errorCode) {
        case 200:
            return json($message)->code(200);
        case 201:
            return response($message)->code(201);
        case 204:
            return response($message)->code(204);
        case $errorCode >= 4000 && $errorCode < 5000:
            $passToCode = intval(substr($errorCode, 0, strlen($errorCode) - 1));

            return json(['code' => $errorCode, 'error' => $message])->code($passToCode);
        default:
            return null;
    }
}

if (!function_exists('jsonCross')) {
    function jsonCross($data = [], $code = 200, $header = [], $options = [])
    {
        $crossDomain = [
                'access-control-allow-origin' => 'http://localhost:8000',
                'access-control-allow-methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
                'access-control-allow-headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
                'access-control-allow-credentials' => 'true',
        ];

        return Response::create($data, 'json', $code)->header(array_merge($crossDomain, $header))->options($options);
    }
}
if (!function_exists('resJson')) {
    /**
     * Api Response Json.
     *
     * @return Response
     */
    function resJson(int $httpCode, array $data = [], string $errMsg = '', array $header = [])
    {
        $initBody = ['success' => true, 'message' => $errMsg, 'data' => $data];
        if ($httpCode >= 300) {
            $initBody['success'] = false;
            $initBody['message'] = $errMsg;
        }

        return jsonCross($initBody, $httpCode, $header);
    }
}
