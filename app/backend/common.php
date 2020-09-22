<?php

use think\Response;
use think\facade\Config;

function buildResponse($data = [], $code = 200, $header = [], $options = [])
{
    return Response::create($data, 'json', $code)->header(array_merge(Config::get('route.default_header'), $header))->options($options);
}

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

    return buildResponse($initBody, $httpCode, $header);
}


function resSuccess(string $message = '', array $data = [], array $header = [])
{
    $httpBody = ['success' => true, 'message' => $message, 'data' => $data];
    return buildResponse($httpBody, 200, $header);
}


function resError(string $message = '', array $data = [], array $header = [])
{
    $httpBody = ['success' => false, 'message' => $message, 'data' => $data];
    return buildResponse($httpBody, 200, $header);
}


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

/* Recursive branch extrusion */
function createTreeBranch(&$parents, $children, $depth = 0)
{
    $tree = [];
    $depth++;
    foreach ($children as $child) {
        $child['depth'] = $depth;
        if (isset($parents[$child['id']])) {
            $child['children'] =
            createTreeBranch($parents, $parents[$child['id']], $depth);
        }
        $tree[] = $child;
    }
    return $tree;
}

/**
* convert Array to Tree structure
* @link https://stackoverflow.com/a/22020668/8819175
* @return array
*/
function arrayToTree($flat, $root = 0)
{
    $parents = [];
    foreach ($flat as $a) {
        $parents[$a['parent_id']][] = $a;
    }
    return createTreeBranch($parents, $parents[$root]);
}


function isMultiArray($array)
{
    $multiCount = array_filter($array, 'is_array');
    return count($multiCount) > 0;
}

/**
 * Extract particular key/column values ​​from an array.
 * @param array $array
 * @param string $targetKeyName
 * @param string $parentKeyName
 * @param bool $unique
 * @return array
 */
function extractValues(array $array = [], string $targetKeyName = 'id', string $parentKeyName = '', bool $unique = true)
{
    // Depth: level two
    if ($parentKeyName) {
        $result = [];
        if ($array) {
            foreach ($array as $key => $value) {
                if ($value[$parentKeyName]) {
                    if (isset($value[$parentKeyName][$targetKeyName])) {
                        $result[] = $value[$parentKeyName][$targetKeyName];
                    } elseif (is_array($value[$parentKeyName])) {
                        $result = array_merge($result, array_column($value[$parentKeyName], $targetKeyName));
                    }
                }
            }
            if (!$unique) {
                return $result;
            }
            if (isMultiArray($result)) {
                return array_unique($result, SORT_REGULAR);
            } else {
                return array_unique($result);
            }
        }
        return $result;
    }

    // Depth: level 1
    if ($array) {
        if (!$unique) {
            return array_column($array, $targetKeyName);
        }
        if (isMultiArray($array)) {
            return array_unique(array_column($array, $targetKeyName), SORT_REGULAR);
        } else {
            return array_unique(array_column($array, $targetKeyName));
        }
    }
    return [];
}
