<?php

namespace app\api\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'id' => 'require|number',
        'ids' => 'require|numberArray',
        'admin_name' => 'require|length:6,32',
        'password' => 'require|length:6,32',
        'display_name' => 'length:4,32',
        'status' => 'numberTag',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'require|dateTimeRange',
        'revisionId' => 'require|number',
        'groups' => 'numberTag',
    ];

    protected $message = [
        'id.require' => 'id#require',
        'id.number' => 'id#number',
        'ids.require' => 'ids#require',
        'ids.numberArray' => 'ids#numberArray',
        'admin_name.require' => 'admin@admin_name#require',
        'admin_name.length' => 'admin@admin_name#length:6,32',
        'password.require' => 'admin@password#require',
        'password.length' => 'admin@password#length:6,32',
        'display_name.length' => 'admin@display_name#length:4,32',
        'status.numberTag' => 'status#numberTag',
        'page.number' => 'page#number',
        'per_page.number' => 'per_page#number',
        'create_time.require' => 'create_time#require',
        'create_time.dateTimeRange' => 'create_time#dateTimeRange',
        'revisionId.require' => 'revisionId#require',
        'revisionId.number' => 'revisionId#number',
        'groups.numberTag' => 'admin@groups#numberTag',
    ];

    protected $scene = [
        'save' => ['admin_name', 'password', 'display_name', 'create_time', 'status'],
        'update' => ['id', 'display_name', 'create_time', 'status'],
        'read' => ['id'],
        'delete' => ['ids'],
        'restore' => ['ids'],
        'add' => [''],
        'login' => [''],
        'logout' => [''],
        'info' => [''],
        'i18n_read' => ['id'],
        'i18n_update' => ['id'],
        'revision_home' => ['page', 'per_page'],
        'revision_restore' => ['revisionId'],
        'revision_read' => [''],
    ];

    public function sceneHome()
    {
        $this->only(['page', 'per_page', 'id', 'status', 'create_time', 'groups'])
            ->remove('id', 'require')
            ->remove('create_time', 'require');
    }
}
