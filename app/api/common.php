<?php

function validateDateTime($date, $format = 'Y-m-d H:i:s')
{
    if ($date) {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    return false;
}

function getSortParam($data, $allowSort)
{
    $sort = [
        'name' => 'id',
        'order' => 'desc',
    ];

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
    return is_array($data) ? array_intersect_key($data, array_flip($allowSearch)) : [];
}

function getFieldNameByFunctionName($functionName)
{
    return parse_name(substr(substr($functionName, 6), 0, -4));
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
* convert Array to Tree
* @link https://stackoverflow.com/a/22020668/8819175
* @return array
*/
function arrayToTree($flat, $root = 0)
{
    if (isTreeArray($flat)) {
        // if parent_id not exist, set them to zero
        $allIds = array_column($flat, 'id');
        $flat = array_map(function ($row) use ($allIds) {
            // parent_id < 0 means self-config, ignore
            if ($row['parent_id'] > 0 && !in_array($row['parent_id'], $allIds)) {
                $row['parent_id'] = 0;
            }
            return $row;
        }, $flat);

        $parents = [];
        foreach ($flat as $a) {
            $parents[$a['parent_id']][] = $a;
        }
        
        // if root does not exist
        if (!isset($parents[$root])) {
            return [];
        }
        return createTreeBranch($parents, $parents[$root]);
    }
    return [];
}

function isTreeArray($array = [])
{
    if (is_array($array) && isset($array[0]['id']) && isset($array[0]['parent_id'])) {
        return true;
    }
    return false;
}

function isMultiArray($array)
{
    $multiCount = array_filter($array, 'is_array');
    return count($multiCount) > 0;
}

/**
 * Extract some key values ​​from the array.
 * TODO: rewrite & improve
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
        if (!empty($array)) {
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
    if (!empty($array)) {
        if (!$unique) {
            return array_column($array, $targetKeyName);
        }
        return array_unique(array_column($array, $targetKeyName), SORT_REGULAR);
    }
    return [];
}

// TODO:rewrite & improve
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
        if (isset($array[$wantedColumn])) {
            return [$array[$wantedColumn]];
        } else {
            return [];
        }
    }

    return findFieldInDescendant($wantedColumn, $array['children']);
}

// TODO:rewrite & improve
function findSubArray($value, string $field, $treeStructureArray = [])
{

    $array = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($treeStructureArray));

    $result = [];
    foreach ($array as $subArray) {
        $subArray = $array->getSubIterator();
        if (isset($subArray[$field]) && $subArray[$field] == $value) {
            $result = iterator_to_array($subArray);
        }
    }

    return $result;
}

// TODO:rewrite & improve
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
