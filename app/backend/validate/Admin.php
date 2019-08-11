<?php

namespace app\backend\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule =   [
        'id'            =>  'require|number',
        'username'      =>  'require|max:32',
        'password'      =>  'require|max:32',
        'display_name'  =>  'max:32',
        'status'        =>  'number',
    ];

    protected $message  =   [
        'id.require'        => 'ID field is empty.',
        'id.number'         => 'ID must be numbers only.',
        'username.require'  => 'The username field is empty.',
        'username.max'      => 'Username field exceeds maximum length allowed. (32)',
        'password.require'  => 'The password field is empty.',
        'password.max'      => 'Username field exceeds maximum length allowed. (32)',
        'display_name.max'  => 'Display Name field exceeds maximum length allowed. (32)',
        'status.number'     => 'Status must be numbers only.',
    ];

    protected $scene = [
        'login'         =>  ['username', 'password'],
        'save'          =>  ['username', 'password', 'display_name', 'status'],
        'read'          =>  ['id'],
        'update'        =>  ['id', 'password', 'display_name', 'status'],
        'delete'        =>  ['id'],
    ];
}
