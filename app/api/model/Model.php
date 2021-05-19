<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\TPAntdBuilder\Builder;

class Model extends Common
{
    protected $json = ['data'];
    protected $jsonAssoc = true;
    protected $readonly = ['id', 'title', 'table_name', 'route_name'];
    protected $unique = ['table_name' => 'Table Name', 'route_name' => 'Route Name'];

    public $allowHome = ['title', 'table_name', 'route_name', 'data'];
    public $allowList = ['title', 'table_name', 'route_name', 'data'];
    public $allowRead = ['title', 'table_name', 'route_name', 'data'];
    public $allowSave = ['title', 'table_name', 'route_name', 'data'];
    public $allowUpdate = ['data'];
    public $allowSearch = ['title', 'table_name', 'route_name'];
    public $allowTranslate = ['title'];

    // Mutator
    public function setRouteNameAttr($value)
    {
        $modelDataField = new \StdClass();
        $modelDataField->routeName = $value;
        $this->set('data', $modelDataField);
        return strtolower($value);
    }
}
