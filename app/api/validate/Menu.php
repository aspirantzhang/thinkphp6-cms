<?php

namespace app\api\validate;

use think\Validate;

class Menu extends Validate
{
    protected $rule = [
        'id' => 'require|number',
        'parent_id' => 'number|checkParentId',
        'status' => 'numberArray',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'dateTimeRange',
        'revisionId' => 'require|number',
        'menu_title' => 'require|length:2,32',
    ];

    protected $message = [
        'id.require' => 'id#require',
        'id.number' => 'id#number',
        'parent_id.number' => 'parent_id#number',
        'parent_id.checkParentId' => 'parent_id#checkParentId',
        'status.numberArray' => 'status#numberArray',
        'page.number' => 'page#number',
        'per_page.number' => 'per_page#number',
        'create_time.dateTimeRange' => 'create_time#dateTimeRange',
        'revisionId.require' => 'revisionId#require',
        'revisionId.number' => 'revisionId#number',
        'menu_title.require' => 'menu@menu_title#require',
        'menu_title.length' => 'menu@menu_title#length:2,32',
    ];

    protected $scene = [
        'save' => ['create_time', 'parent_id', 'menu_title'],
        'update' => ['id', 'create_time', 'parent_id', 'menu_title'],
        'read' => ['id'],
        'delete' => ['ids'],
        'restore' => ['ids'],
        'add' => [''],
        'backend' => [''],
        'i18n_read' => ['id'],
        'i18n_update' => ['id'],
        'revision_home' => ['page', 'per_page'],
        'revision_restore' => ['revisionId'],
        'revision_read' => [''],
    ];

    public function sceneHome()
    {
        $this->only(['page', 'per_page', 'id', 'create_time'])
            ->remove('id', 'require')
            ->remove('create_time', 'require');
    }
}
