<?php

// 应用公共文件

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
