<?php

namespace app\backend\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule =   [
        'id'            =>  'require|number',
        'username'      =>  'require|length:6,32',
        'password'      =>  'require|length:6,32',
        'display_name'  =>  'length:4,32',
        'status'        =>  'in:0,1',
    ];

    protected $message  =   [
        'id.require'        => 'ID field is empty.',
        'id.number'         => 'ID must be numbers only.',
        'username.require'  => 'The username field is empty.',
        'username.length'      => 'Username length should be between 6 and 32.',
        'password.require'  => 'The password field is empty.',
        'password.length'      => 'Password length should be between 6 and 32.',
        'display_name.length'  => 'Display Name length should be between 4 and 32.',
        'status.in'     => 'Status value should be 0 or 1.',
    ];

    protected $scene = [
        'login'         =>  ['username', 'password'],
        'index'         =>  [''],
        'save'          =>  ['username', 'password', 'display_name', 'status'],
        'read'          =>  ['id'],
        'update'        =>  ['id', 'password', 'display_name', 'status'],
        'delete'        =>  ['id'],
    ];
}
