<?php

declare(strict_types=1);

namespace app\api\model;

use app\api\service\Admin as AdminService;
use app\api\service\AuthRule as AuthRuleService;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthGroup extends Common
{
    protected $readonly = ['id'];
    protected $unique = ['group_name' => 'Group Name'];
    protected $titleField = 'group_name';

    public $allowHome = ['parent_id', 'group_name', 'rules'];
    public $allowList = ['parent_id', 'group_name', 'rules'];
    public $allowRead = ['parent_id', 'group_name', 'rules'];
    public $allowSave = ['parent_id', 'group_name', 'rules'];
    public $allowUpdate = ['parent_id', 'group_name', 'rules'];
    public $allowSearch = ['parent_id', 'group_name', 'rules'];

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
    public function searchGroupNameAttr($query, $value)
    {
        $query->where('group_name', 'like', '%' . $value . '%');
    }
}
