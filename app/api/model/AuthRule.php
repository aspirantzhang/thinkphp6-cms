<?php

declare(strict_types=1);

namespace app\api\model;

use app\api\service\AuthGroup as AuthGroupService;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthRule extends Common
{
    protected $readonly = ['id'];
    protected $unique = [];
    protected $titleField = 'rule_title';

    public $allowHome = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];
    public $allowList = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];
    public $allowRead = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];
    public $allowSave = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];
    public $allowUpdate = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];
    public $allowSearch = ['parent_id', 'rule_path', 'rule_title', 'type', 'condition'];
    public $allowTranslate = ['rule_title'];

    protected function setAddonData($params = [])
    {
        return [
            'parent_id' => $this->treeDataAPI([], [], $params['id'] ?? 0)
        ];
    }

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroupService::class, 'auth_group_rule', 'group_id', 'rule_id');
    }

    // Accessor

    // Mutator

    // Searcher
}
