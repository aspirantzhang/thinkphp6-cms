<?php

function getSortParam(array $data, array $allowSort): array
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

function getListParams(array $params, array $allowHome, array $allowSort): array
{
    $result = [];
    $result['trash'] = $params['trash'] ?? 'withoutTrashed';
    $result['per_page'] = $params['per_page'] ?? 10;
    $result['visible'] = is_array($allowHome) ? array_diff($allowHome, ['sort', 'order', 'page', 'per_page', 'trash']) : [];
    $result['search']['values'] = is_array($params) ? array_intersect_key($params, array_flip($result['visible'])) : [];
    $result['search']['keys'] = array_keys($result['search']['values']);
    $result['sort'] = getSortParam($params, $allowSort);
    return $result;
}

function getFieldNameBySearcherName(string $functionName): string
{
    return parse_name(substr(substr($functionName, 6), 0, -4));
}
