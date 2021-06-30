<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Model extends Common
{
    protected $json = ['data'];
    protected $jsonAssoc = true;
    protected $readonly = ['id', 'model_title', 'model_name'];
    protected $uniqueField = ['model_title', 'model_name'];

    // Mutator
    public function setModelNameAttr($value)
    {
        $data = [];
        $data['layout']['modelName'] = $value;
        $this->set('data', $data);
        return strtolower($value);
    }
}
