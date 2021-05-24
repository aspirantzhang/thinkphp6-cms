<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Model extends Common
{
    protected $json = ['data'];
    protected $jsonAssoc = true;
    protected $readonly = ['id', 'model_title', 'table_name', 'route_name'];
    protected $unique = ['table_name' => 'Table Name', 'route_name' => 'Route Name'];

    // Mutator
    public function setRouteNameAttr($value)
    {
        $modelDataField = new \StdClass();
        $modelDataField->routeName = $value;
        $this->set('data', $modelDataField);
        return strtolower($value);
    }
}
