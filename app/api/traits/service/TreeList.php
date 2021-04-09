<?php

declare(strict_types=1);

namespace app\api\traits\service;

trait TreeList
{
    public function treeListAPI($params, $withRelation = [])
    {
        $params['trash'] = $params['trash'] ?? 'withoutTrashed';
       
        $layout = $this->listBuilder($this->getAddonData(), $params);
        $layout['page']['trash'] = $params['trash'] == 'onlyTrashed' ? true : false;
        $layout['dataSource'] = [];
        $layout['meta'] = [
            'total' => 0,
            'per_page' => 10,
            'page' => 1,
        ];
        $data = $this->getListData($params, $withRelation);
        if ($data) {
            if (isTreeArray($data)) {
                $layout['dataSource'] = arrayToTree($data);
            } else {
                return $this->error('Data loading error.');
            }
        }
        return $this->success('', $layout);
    }

    /**
     * Get the tree structure of the list data
     * @param mixed $params e.g.: ['status' => 1]
     * @return array
     */
    public function treeDataAPI($params = [], $withRelation = [])
    {
        $params['trash'] = $params['trash'] ?? 'withoutTrashed';
        $data = $this->getListData($params, $withRelation);
        if ($data) {
            if (!isset($data[0]['parent_id']) || !isset($this->titleField)) {
                return [];
            }
            $data = array_map(function ($model) {
                $treeMenu = [
                    'id' => $model['id'],
                    'value' => $model['id'],
                    'title' => $model[$this->titleField],
                    'parent_id' => $model['parent_id'],
                ];
                return array_merge($model, $treeMenu);
            }, $data);

            return arrayToTree($data);
        }
        return [];
    }
}
