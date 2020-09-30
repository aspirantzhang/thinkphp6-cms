<?php

namespace app\backend\validate;

use think\Validate;

class AuthRule extends Validate
{
    protected $rule = [
        'id' => 'require|number',
        'ids' => 'require|numberArray',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'require|dateTimeRange',
        'name' => 'require',
    ];

    protected $message = [
        'id.require' => 'ID field is empty.',
        'id.number' => 'ID must be numbers only.',
        'ids.require' => 'IDs field is empty.',
        'ids.numberArray' => 'IDs must be a number array.',
        'page.number' => 'Page must be numbers only.',
        'per_page.number' => 'Per_page must be numbers only.',
        'create_time.require' => 'Create time is empty.',
        'create_time.dateTimeRange' => 'Invalid create time format.',
        'name.require' => 'Name field is empty.',
    ];

    protected $scene = [
        'save' => ['create_time', 'name'],
        'update' => ['id', 'create_time', 'name'],
        'read' => ['id'],
        'delete' => ['ids'],
        'restore' => ['ids'],
        'add' => [''],
    ];

    public function sceneHome()
    {
        $this->only(['page', 'per_page', 'id', 'create_time'])
            ->remove('id', 'require')
            ->remove('create_time', 'require');
    }
}
