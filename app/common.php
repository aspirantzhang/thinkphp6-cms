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
        $sort['order'] = ($data['order'] == 'asc') ? 'asc' : 'desc';
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

function msg($errorCode, $message=null)
{
    switch ($errorCode) {
        case 200:
            return json($message)->code(200);
            break;
        case 201:
            return response($message)->code(201);
            break;
        case 204:
            return response($message)->code(204);
            break;

        case $errorCode>=4000 && $errorCode<5000:
            return json(['code'=>$errorCode, 'error'=>$message])->code(substr($errorCode,0,strlen($errorCode)-1));
            break;

        default:
            # code...
            break;
    }
}