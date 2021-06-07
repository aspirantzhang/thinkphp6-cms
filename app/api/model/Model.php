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
        $modelDataField = new \StdClass();
        $modelDataField->modelName = $value;
        $this->set('data', $modelDataField);
        return strtolower($value);
    }
}
