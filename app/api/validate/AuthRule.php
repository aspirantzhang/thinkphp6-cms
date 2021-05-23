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
        'rule_path' => 'length:0,255',
    ];

    protected $message = [
        'id.require' => 'id#require',
        'parent_id.number' => 'parent_id#number',
        'id.number' => 'id#number',
        'ids.require' => 'ids#require',
        'ids.numberArray' => 'ids#numberArray',
        'page.number' => 'page#number',
        'per_page.number' => 'per_page#number',
        'create_time.require' => 'create_time#require',
        'create_time.dateTimeRange' => 'create_time#dateTimeRange',
        'rule_title.require' => 'rule@rule_title#require',
        'rule_path.length' => 'rule@rule_path#length:0,255'
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
