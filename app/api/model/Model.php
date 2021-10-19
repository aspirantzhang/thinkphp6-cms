<?php

declare(strict_types=1);

namespace app\api\model;

class Model extends Common
{
    protected $json = ['data'];
    protected $jsonAssoc = true;
    protected $readonly = ['id', 'model_title', 'table_name'];

    protected function setAddonData($params = [])
    {
        return [
            'type' => [
                [
                    'title' => '主模型',
                    'value' => 1
                ],
                [
                    'title' => '分类模型',
                    'value' => 2
                ],
            ],
            'parent_id' => $this->treeDataAPI([], [], $params['id'] ?? 0)
        ];
    }

    // Mutator
    public function setTableNameAttr($value)
    {
        $data = [];
        $data['layout']['tableName'] = strtolower($value);
        $this->set('data', $data);
        return strtolower($value);
    }
}
