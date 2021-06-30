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

        $data = $this->addTranslationStatus($data);

        if ($data) {
            if (isTreeArray($data)) {
                $layout['dataSource'] = arrayToTree($data);
            } else {
                return $this->error(__('unable to load page data'));
            }
        }
        return $this->success('', $layout);
    }

    /**
     * Get the tree structure of the list data
     * @param mixed $params e.g.: ['status' => 1]
     * @return array
     */
    public function treeDataAPI($params = [], $withRelation = [], $parentTreeExceptId = 0)
    {
        $params['trash'] = $params['trash'] ?? 'withoutTrashed';
        $data = $this->getListData($params, $withRelation);

        if (!empty($data)) {
            if (!isTreeArray($data) || !isset($this->titleField)) {
                $this->error = __('invalid data structure');
                return [];
            }
            $data = array_map(function ($model) use ($parentTreeExceptId) {
                $treeMenu = [
                    'id' => $model['id'],
                    'key' => $model['id'],
                    'value' => $model['id'],
                    'title' => $model[$this->titleField],
                    'parent_id' => $model['parent_id'],
                ];
                if ($model['id'] === (int)$parentTreeExceptId) {
                    $treeMenu['disabled'] = true;
                }
                return array_merge($model, $treeMenu);
            }, $data);

            if ((bool)$parentTreeExceptId) {
                $top = [
                    'id' => 0,
                    'key' => 0,
                    'value' => 0,
                    'title' => 'Top',
                    'parent_id' => -1,
                ];
                $data = array_merge([$top], $data);

                return arrayToTree($data, -1);
            } else {
                return arrayToTree($data);
            }
        }
        return [];
    }
}
