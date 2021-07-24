<?php

declare(strict_types=1);

namespace app\api\service;

use app\api\logic\Menu as MenuLogic;

class Menu extends MenuLogic
{
    public function treeDataAPI($params = [], $withRelation = [], $parentTreeExceptId = 0)
    {
        $params['trash'] = $params['trash'] ?? 'withoutTrashed';
        $data = $this->getListData($params, $withRelation);
        
        if (!empty($data)) {
            if (!isTreeArray($data)) {
                // TODO:refactor
                return [];
            }
            $data = array_map(function ($model) use ($parentTreeExceptId) {
                $treeMenu = [
                    'id' => $model['id'],
                    'value' => $model['id'],
                    'name' => $model[$this->getTitleField()],
                    'title' => $model[$this->getTitleField()],
                    'parent_id' => $model['parent_id'],
                    'hideInMenu' => $model['hide_in_menu'],
                    'hideChildrenInMenu' => $model['hide_children_in_menu'],
                    'flatMenu' => $model['flat_menu'],
                ];
                if ($model['id'] === (int)$parentTreeExceptId) {
                    $treeMenu['disabled'] = true;
                }
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

            if ((bool)$parentTreeExceptId) {
                $top = [
                    'id' => 0,
                    'key' => 0,
                    'value' => 0,
                    'title' => 'Top',
                    'parent_id' => -1,
                ];
                $newData = array_merge([$top], $newData);

                return arrayToTree($newData, -1);
            }
            return arrayToTree($newData);
        }
        return [];
    }
}
