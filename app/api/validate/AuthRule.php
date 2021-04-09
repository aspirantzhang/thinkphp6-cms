<?php

namespace app\api\validate;

use think\Validate;

class AuthRule extends Validate
{
    protected $rule = [
        'id' => 'require|number',
        'parent_id' => 'number',
        'ids' => 'require|numberArray',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'require|dateTimeRange',
        'rule_title' => 'require',
    ];

    protected $message = [
        'id.require' => 'ID field is empty.',
        'parent_id.number' => 'Parent ID must be numbers only.',
        'id.number' => 'ID must be numbers only.',
        'ids.require' => 'IDs field is empty.',
        'ids.numberArray' => 'IDs must be a number array.',
        'page.number' => 'Page must be numbers only.',
        'per_page.number' => 'Per_page must be numbers only.',
        'create_time.require' => 'Create time is empty.',
        'create_time.dateTimeRange' => 'Invalid create time format.',
        'rule_title.require' => 'Rule title field is empty.',
    ];

    protected $scene = [
        'save' => ['create_time', 'parent_id', 'rule_title'],
        'update' => ['id', 'create_time', 'parent_id', 'rule_title'],
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
