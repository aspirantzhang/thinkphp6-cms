<?php

namespace app\api\validate;

use think\Validate;

class AuthGroup extends Validate
{
    protected $rule = [
        'id' => 'require|number',
        'parent_id' => 'number',
        'ids' => 'require|numberArray',
        'group_name' => 'require|length:6,32',
        'rules' => 'length:0,255',
        'status' => 'numberTag',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'require|dateTimeRange',
    ];

    protected $message = [
        'id.require' => 'ID field is empty.',
        'id.number' => 'ID must be numbers only.',
        'parent_id.number' => 'Parent ID must be numbers only.',
        'ids.require' => 'IDs field is empty.',
        'ids.numberArray' => 'IDs must be a number array.',
        'group_name.require' => 'The group name field is empty.',
        'group_name.length' => 'Group name length should be between 6 and 32.',
        'rules.length' => 'Rules length should be between 0 and 255.',
        'status.numberTag' => 'Invalid status format.',
        'page.number' => 'Page must be numbers only.',
        'per_page.number' => 'Per_page must be numbers only.',
        'create_time.require' => 'Create time is empty.',
        'create_time.dateTimeRange' => 'Invalid create time format.',
    ];

    // index save read update delete

    protected $scene = [
        'save' => ['parent_id', 'group_name', 'rules', 'create_time', 'status'],
        'update' => ['id', 'parent_id', 'group_name', 'rules', 'create_time', 'status'],
        'read' => ['id'],
        'delete' => ['ids'],
        'restore' => ['ids'],
        'add' => [''],
    ];

    public function sceneHome()
    {
        $this->only(['page', 'per_page', 'id', 'status', 'create_time'])
            ->remove('id', 'require')
            ->remove('create_time', 'require');
    }
}
