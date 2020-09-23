<?php

namespace app\backend\validate;

use think\Validate;

class AuthRule extends Validate
{
    protected $rule = [
        'id' => 'require|number',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'require|dateTimeRange',
        'name' => 'require',
        'rule' => 'require',
    ];

    protected $message = [
        'id.require' => 'ID field is empty.',
        'id.number' => 'ID must be numbers only.',
        'page.number' => 'Page must be numbers only.',
        'per_page.number' => 'Per_page must be numbers only.',
        'create_time.require' => 'Create time is empty.',
        'create_time.dateTimeRange' => 'Invalid create time format.',
        'name.require' => 'Name field is empty.',
        'rule.require' => 'Rule field is empty.',
    ];

    protected $scene = [
        'save' => ['create_time', 'name', 'rule'],
        'update' => ['id', 'create_time', 'name', 'rule'],
        'read' => ['id'],
        'delete' => ['id'],
        'add' => [''],
        'batch_delete' => [''],
    ];

    public function sceneHome()
    {
        $this->only(['page', 'per_page', 'id', 'create_time'])
            ->remove('id', 'require')
            ->remove('create_time', 'require');
    }
}
