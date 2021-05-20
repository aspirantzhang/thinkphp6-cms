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

    public $allowHome = ['model_title', 'table_name', 'route_name', 'data'];
    public $allowList = ['model_title', 'table_name', 'route_name', 'data'];
    public $allowRead = ['model_title', 'table_name', 'route_name', 'data'];
    public $allowSave = ['model_title', 'table_name', 'route_name', 'data'];
    public $allowUpdate = ['data'];
    public $allowSearch = ['model_title', 'table_name', 'route_name'];
    public $allowTranslate = ['model_title'];

    // Mutator
    public function setRouteNameAttr($value)
    {
        $modelDataField = new \StdClass();
        $modelDataField->routeName = $value;
        $this->set('data', $modelDataField);
        return strtolower($value);
    }
}
