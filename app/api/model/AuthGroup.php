<?php

declare(strict_types=1);

namespace app\api\model;

use app\api\service\Admin as AdminService;
use app\api\service\AuthRule as AuthRuleService;

class AuthGroup extends Common
{
    protected $readonly = ['id'];

    protected function setAddonData($params = [])
    {
        return [
            'rules' => (new AuthRuleService())->treeDataAPI(['status' => 1]),
            'parent_id' => $this->treeDataAPI([], [], $params['id'] ?? 0)
        ];
    }

    // Relation
    public function admins()
    {
        return $this->belongsToMany(AdminService::class, 'auth_admin_group', 'admin_id', 'group_id');
    }

    public function rules()
    {
        return $this->belongsToMany(AuthRuleService::class, 'auth_group_rule', 'rule_id', 'group_id');
    }

    // Accessor

    // Mutator

    // Searcher
    public function searchGroupTitleAttr($query, $value)
    {
        $query->where('group_title', 'like', '%' . $value . '%');
    }
}
