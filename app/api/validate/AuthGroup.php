<?php

namespace app\api\validate;

use think\Validate;
use think\facade\Request;

class AuthGroup extends Validate
{
    protected $rule = [
        'id' => 'require|number',
        'parent_id' => 'number|checkParentId',
        'ids' => 'require|numberArray',
        'group_title' => 'require|length:2,32',
        'rules' => 'length:0,255',
        'status' => 'numberArray',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'require|dateTimeRange',
        'revisionId' => 'require|number',
    ];

    protected $message = [
        'id.require' => 'id#require',
        'id.number' => 'id#number',
        'parent_id.number' => 'parent_id#number',
        'parent_id.checkParentId' => 'parent_id#checkParentId',
        'ids.require' => 'ids#require',
        'ids.numberArray' => 'ids#numberArray',
        'group_title.require' => 'auth_group@group_title#require',
        'group_title.length' => 'auth_group@group_title#length:2,32',
        'rules.length' => 'auth_group@rules#length:0,255',
        'status.numberArray' => 'status#numberArray',
        'page.number' => 'page#number',
        'per_page.number' => 'per_page#number',
        'create_time.require' => 'create_time#require',
        'create_time.dateTimeRange' => 'create_time#dateTimeRange',
        'revisionId.require' => 'revisionId#require',
        'revisionId.number' => 'revisionId#number',
    ];

    protected $scene = [
        'save' => ['parent_id', 'group_title', 'rules', 'create_time', 'status'],
        'update' => ['id', 'parent_id', 'group_title', 'rules', 'create_time', 'status'],
        'read' => ['id'],
        'delete' => ['ids'],
        'restore' => ['ids'],
        'add' => [''],
        'i18n_read' => ['id'],
        'i18n_update' => ['id'],
        'revision_home' => ['page', 'per_page'],
        'revision_restore' => ['revisionId'],
        'revision_read' => [''],
    ];

    public function sceneHome()
    {
        $this->only(['page', 'per_page', 'id', 'status', 'create_time'])
            ->remove('id', 'require')
            ->remove('create_time', 'require');
    }
}
