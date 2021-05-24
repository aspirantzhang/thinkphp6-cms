<?php

namespace app\api\validate;

use think\Validate;

class Model extends Validate
{

    protected $rule = [
        'id' => 'require|number',
        'ids' => 'require|numberArray',
        'status' => 'numberTag',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'require|dateTimeRange',
        'model_title' => 'require|length:2,32',
        'table_name' => 'require|length:2,10|checkRouteName',
        'route_name' => 'require|length:2,10|checkRouteName',
    ];

    protected $message = [
        'id.require' => 'id#require',
        'id.number' => 'id#number',
        'ids.require' => 'ids#require',
        'ids.numberArray' => 'ids#numberArray',
        'status.numberTag' => 'status#numberTag',
        'page.number' => 'page#number',
        'per_page.number' => 'per_page#number',
        'create_time.require' => 'create_time#require',
        'create_time.dateTimeRange' => 'create_time#dateTimeRange',
        'model_title.require' => 'model@model_title#require',
        'model_title.length' => 'model@model_title#length:2,32',
        'table_name.require' => 'model@table_name#require',
        'table_name.length' => 'model@table_name#length:2,10',
        'table_name.checkRouteName' => 'model@table_name#checkRouteName',
        'route_name.require' => 'model@route_name#require',
        'route_name.length' => 'model@route_name#length:2,10',
        'route_name.checkRouteName' => 'model@route_name#checkRouteName',
    ];

    protected $scene = [
        'save' => ['model_title', 'table_name', 'route_name', 'create_time', 'status'],
        'update' => ['id'],
        'read' => ['id'],
        'delete' => ['ids'],
        'restore' => ['ids'],
        'add' => [''],
        'design' => [''],
        'design_update' => [''],
    ];

    public function sceneHome()
    {
        $this->only(['page', 'per_page', 'id', 'status', 'create_time'])
            ->remove('id', 'require')
            ->remove('create_time', 'require');
    }

    protected function checkRouteName($value)
    {
        if (preg_match("/^[a-zA-z0-9_-]+$/i", $value) == 1) {
            return true;
        } else {
            return false;
        }
    }
}
