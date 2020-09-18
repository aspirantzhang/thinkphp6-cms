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

function extractUniqueValues(array $array = [], string $keyName = 'id')
{
    $result = [];
    if ($array) {
        return array_column($array, $keyName);
    }
    return [];
}

function extractUniqueValuesInArray(array $array, string $parentKeyName, string $targetKeyName)
{
    $result = [];
    if (count($array)) {
        foreach ($array as $key => $value) {
            if (count($value[$parentKeyName])) {
                $result = array_merge($result, array_column($value[$parentKeyName], $targetKeyName));
            }
        }
        return $result;
    }
    return [];
}

/**
 * Extract a particular key's value from a associated array to a indexed array
 * @param array $assocArray
 * @param string $key Key Name
 * @return array return a indexed array
 */
function extractFromAssocToIndexed(array $assocArray, string $key): array
{
    $indexed = [];
    if (!empty($assocArray)) {
        foreach ($assocArray as $assoc) {
            $indexed[] = $assoc[$key];
        }
    }
    return $indexed;
}
