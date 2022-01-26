<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\octopusModelCreator\ModelCreator;

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
    public function setTableNameAttr($value, $data)
    {
        $this->set('data', ModelCreator::db()->initModelDataField($data));
        return strtolower($value);
    }
}
