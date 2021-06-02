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
        'model_name' => 'require|length:2,10|checkModelName',
        'type' => 'require',
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
        'model_name.require' => 'model@model_name#require',
        'model_name.length' => 'model@model_name#length:2,10',
        'model_name.checkModelName' => 'model@model_name#checkModelName',
        'type.require' => 'model@type#require',
    ];

    protected $scene = [
        'save' => ['model_title', 'model_name', 'create_time', 'status'],
        'update' => ['id'],
        'read' => ['id'],
        'delete' => ['ids'],
        'restore' => ['ids'],
        'add' => [''],
        'design' => [''],
        'design_update' => ['id', 'type'],
    ];

    public function sceneHome()
    {
        $this->only(['page', 'per_page', 'id', 'status', 'create_time'])
            ->remove('id', 'require')
            ->remove('create_time', 'require');
    }

    protected function checkModelName($value)
    {
        if (preg_match("/^[a-z0-9_-]+$/i", $value) == 1) {
            return true;
        }
        return false;
    }
}
