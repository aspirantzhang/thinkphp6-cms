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
                    'title' => __('model.main_model'),
                    'value' => 1
                ],
                [
                    'title' => __('model.category_model'),
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
