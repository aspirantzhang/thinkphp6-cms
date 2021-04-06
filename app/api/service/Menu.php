<?php

declare(strict_types=1);

namespace app\api\service;

use app\api\logic\Menu as MenuLogic;

class Menu extends MenuLogic
{
    public function treeDataAPI($params = [], $withRelation = [])
    {
        $params['trash'] = $params['trash'] ?? 'withoutTrashed';
        $data = $this->getListData($params, $withRelation);
        if (!empty($data)) {
            if (!isset($data[0]['parent_id'])) {
                return [];
            }
            $data = array_map(function ($model) {
                $treeMenu = [
                    'id' => $model['id'],
                    'value' => $model['id'],
                    'title' => $model['name'],
                    'parent_id' => $model['parent_id'],
                ];
                return array_merge($model, $treeMenu);
            }, $data);

            // add parentKey
            $newData = [];
            foreach ($data as $arr) {
                $parentArrayKey = array_search($arr['parent_id'], array_column($data, 'id'));
                $parentArray = [];
                if ($parentArrayKey !== false) {
                    $parentArray = $data[$parentArrayKey];
                }
                if (isset($parentArray['path'])) {
                    $arr['parentKeys'] = [$parentArray['path']];
                }
                $newData[] = $arr;
            }

            return arrayToTree($newData);
        }
        return [];
    }
}
