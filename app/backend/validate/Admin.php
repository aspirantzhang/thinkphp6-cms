<?php

namespace app\backend\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'id' => 'require|number',
        'username' => 'require|length:6,32',
        'password' => 'require|length:6,32',
        'display_name' => 'length:4,32',
        'status' => 'numberTag',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'require|dateTimeRange',
    ];

    protected $message = [
        'id.require' => 'ID field is empty.',
        'id.number' => 'ID must be numbers only.',
        'username.require' => 'The username field is empty.',
        'username.length' => 'Username length should be between 6 and 32.',
        'password.require' => 'The password field is empty.',
        'password.length' => 'Password length should be between 6 and 32.',
        'display_name.length' => 'Display Name length should be between 4 and 32.',
        'status.numberTag' => 'Invalid status format.',
        'page.number' => 'Page must be numbers only.',
        'per_page.number' => 'Per_page must be numbers only.',
        'create_time.require' => 'Create time is empty.',
        'create_time.dateTimeRange' => 'Invalid create time format.',
    ];

    // index save read update delete

    protected $scene = [
        'test' => [''],
        'save' => ['username', 'password', 'display_name', 'create_time', 'status'],
        'update' => ['id', 'display_name', 'create_time', 'status'],
        'read' => ['id'],
        'delete' => ['id'],
        'add' => [''],
        'batch_delete' => [''],
    ];

    public function sceneHome()
    {
        $this->only(['page', 'per_page', 'id', 'status', 'create_time'])
            ->remove('id', 'require')
            ->remove('create_time', 'require');
    }
}
