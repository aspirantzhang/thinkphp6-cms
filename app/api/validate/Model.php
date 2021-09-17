<?php

namespace app\api\validate;

use think\Validate;

class Model extends Validate
{
    protected $rule = [
        'id' => 'require|number',
        'ids' => 'require|numberArray',
        'status' => 'numberTag',
        'page' => 'number',
        'per_page' => 'number',
        'create_time' => 'require|dateTimeRange',
        'revisionId' => 'require|number',
        'model_title' => 'require|length:2,32',
        'table_name' => 'require|length:2,10|checkTableName',
        'type' => 'require',
    ];

    protected $message = [
        'id.require' => 'id#require',
        'id.number' => 'id#number',
        'ids.require' => 'ids#require',
        'ids.numberArray' => 'ids#numberArray',
        'status.numberTag' => 'status#numberTag',
        'page.number' => 'page#number',
        'per_page.number' => 'per_page#number',
        'create_time.require' => 'create_time#require',
        'create_time.dateTimeRange' => 'create_time#dateTimeRange',
        'revisionId.require' => 'revisionId#require',
        'revisionId.number' => 'revisionId#number',
        'model_title.require' => 'model@model_title#require',
        'model_title.length' => 'model@model_title#length:2,32',
        'table_name.require' => 'model@table_name#require',
        'table_name.length' => 'model@table_name#length:2,10',
        'table_name.checkTableName' => 'model@table_name#checkTableName',
        'type.require' => 'model@type#require',
    ];

    protected $scene = [
        'save' => ['model_title', 'table_name', 'create_time', 'status'],
        'update' => ['id'],
        'read' => ['id'],
        'delete' => ['ids'],
        'restore' => ['ids'],
        'add' => [''],
        'design' => [''],
        'design_update' => ['id', 'type'],
        'i18n' => ['id'],
        'i18n_update' => ['id'],
        'revision' => ['page', 'per_page'],
        'revision_restore' => ['revisionId'],
    ];

    public function sceneHome()
    {
        $this->only(['page', 'per_page', 'id', 'status', 'create_time'])
            ->remove('id', 'require')
            ->remove('create_time', 'require');
    }

    protected function checkTableName($value)
    {
        if (preg_match("/^[a-z0-9_-]+$/i", $value) == 1) {
            return true;
        }
        return false;
    }
}
