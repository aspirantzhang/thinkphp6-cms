<?php

declare(strict_types=1);

namespace app\api\model;

class Model extends Common
{
    protected $json = ['data'];
    protected $jsonAssoc = true;
    protected $readonly = ['id', 'model_title', 'table_name'];

    // Mutator
    public function setTableNameAttr($value)
    {
        $data = [];
        $data['layout']['tableName'] = strtolower($value);
        $this->set('data', $data);
        return strtolower($value);
    }
}
