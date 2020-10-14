<?php

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


function getSearchParam($data = [], $allowSearch = [])
{
    unset($data['trash']);
    unset($data['sort']);
    unset($data['order']);
    return $data ?? array_intersect_key($data, array_flip($allowSearch));
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
    if ($flat) {
        $parents = [];
        foreach ($flat as $a) {
            $parents[$a['parent_id']][] = $a;
        }
        return createTreeBranch($parents, $parents[$root]);
    }
    return [];
}

function isTreeArray($array = [])
{
    if ($array && !isset($array[0]['parent_id'])) {
        return false;
    }
    return true;
}

function isParentArray($array = [])
{
    if ($array && !isset($array[0]['parent_id']) || !isset($array[0]['id']) || !isset($array[0]['name'])) {
        return false;
    }
    return true;
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
                if (isset($value[$parentKeyName])) {
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


function getDescendantSet(string $wantedColumn, string $findFieldName, $findValue, $treeStructureArray = [], $descendant = true)
{
    if (!$treeStructureArray) {
        return [];
    }
    $array = findSubArray($findValue, $findFieldName, $treeStructureArray);
    if (!$descendant) {
        if (isset($array['children'])) {
            return array_column($array['children'], $wantedColumn);
        } else {
            return [];
        }
    }
    if (!isset($array['children'])) {
        return [$array[$wantedColumn]];
    }

    return findFieldInDescendant($wantedColumn, $array['children']);
}

function findSubArray($value, string $field, $treeStructureArray = [])
{

    $array = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($treeStructureArray));

    $result = [];
    foreach ($array as $subArray) {
        $subArray = $array->getSubIterator();
        if ($subArray[$field] == $value) {
            $result = iterator_to_array($subArray);
        }
    }

    return $result;
}

function findFieldInDescendant(string $field, $array = [])
{
    $result = array_column($array, $field);

    foreach ($array as $arr) {
        if (isset($arr['children'])) {
            $result = array_merge($result, findFieldInDescendant($field, $arr['children']));
        }
    }
    
    return $result;
}

function getSingleChoiceValue($trueText = 'Enabled', $falseText = 'Disabled')
{
    return [0 => $falseText, 1 => $trueText];
}
