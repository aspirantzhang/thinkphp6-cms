<?php

namespace app\api\validate;

use think\Validate;

class Model extends Validate
{

    protected $rule = [
        'id' => 'require|number',
        'ids' => 'require|numberArray',
        'title' => 'require|length:2,32',
        'table_name' => 'require|length:2,32',
        'route_name' => 'require|length:2,32',
        'status' => 'numberTag',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'require|dateTimeRange',
    ];

    protected $message = [
        'id.require' => 'ID field is empty.',
        'id.number' => 'ID must be numbers only.',
        'ids.require' => 'IDs field is empty.',
        'ids.numberArray' => 'IDs must be a number array.',
        'title.require' => 'The model title field is empty.',
        'title.length' => 'Model title length should be between 2 and 32.',
        'table_name.require' => 'The table name field is empty.',
        'table_name.length' => 'Table name length should be between 2 and 32.',
        'route_name.require' => 'The route name field is empty.',
        'route_name.length' => 'Route name length should be between 2 and 32.',
        'status.numberTag' => 'Invalid status format.',
        'page.number' => 'Page must be numbers only.',
        'per_page.number' => 'Per_page must be numbers only.',
        'create_time.require' => 'Create time is empty.',
        'create_time.dateTimeRange' => 'Invalid create time format.',
    ];

    protected $scene = [
        'save' => ['title', 'table_name', 'route_name', 'create_time', 'status'],
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
}
