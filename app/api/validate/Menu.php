<?php

namespace app\api\validate;

use think\Validate;

class Menu extends Validate
{
    protected $rule = [
        'id' => 'require|number',
        'parent_id' => 'number',
        'status' => 'numberTag',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'require|dateTimeRange',
    ];

    protected $message = [
        'id.require' => 'ID field is empty.',
        'id.number' => 'ID must be numbers only.',
        'parent_id.number' => 'Parent ID must be numbers only.',
        'status.numberTag' => 'Invalid status format.',
        'page.number' => 'Page must be numbers only.',
        'per_page.number' => 'Per_page must be numbers only.',
        'create_time.require' => 'Create time is empty.',
        'create_time.dateTimeRange' => 'Invalid create time format.',
    ];

    protected $scene = [
        'save' => ['create_time', 'parent_id'],
        'update' => ['id', 'create_time', 'parent_id'],
        'read' => ['id'],
        'delete' => ['ids'],
        'restore' => ['ids'],
        'add' => [''],
        'backend' => [''],
    ];

    public function sceneHome()
    {
        $this->only(['page', 'per_page', 'id', 'create_time'])
            ->remove('id', 'require')
            ->remove('create_time', 'require');
    }
}
