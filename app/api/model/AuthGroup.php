<?php

declare(strict_types=1);

namespace app\api\model;

use app\api\service\Admin as AdminService;
use app\api\service\AuthRule as AuthRuleService;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthGroup extends Common
{
    protected $readonly = ['id'];
    protected $unique = [];
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
            'parent_id' => arrayToTree($this->getParentData($params['id'] ?? 0), -1),
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

    // Builder
    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('group.group_name')->type('text'),
            Builder::field('parent_id')->type('parent')->data($addonData['parent_id']),
            Builder::field('group.rules')->type('tree')->data($addonData['rules']),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/groups')->method('post'),
        ];

        return Builder::page('group.group-add')
                        ->type('page')
                        ->tab('basic', $basic)
                        ->action('actions', $action)
                        ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('group.group_name')->type('text'),
            Builder::field('parent_id')->type('parent')->data($addonData['parent_id']),
            Builder::field('group.rules')->type('tree')->data($addonData['rules']),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/groups/' . $id)->method('put'),
        ];

        return Builder::page('group-edit')
                        ->type('page')
                        ->tab('basic', $basic)
                        ->action('actions', $action)
                        ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('add')->type('primary')->call('modal')->uri('/api/groups/add'),
            Builder::button('reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [
            Builder::button('delete')->type('danger')->call('delete')->uri('/api/groups/delete')->method('post'),
            Builder::button('disable')->type('default')->call('function')->uri('batchDisableHandler'),
        ];
        if ($this->isTrash($params)) {
            $batchToolBar = [
                Builder::button('deletePermanently')->type('danger')->call('deletePermanently')->uri('/api/groups/delete')->method('post'),
                Builder::button('restore')->type('default')->call('restore')->uri('/api/groups/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('group.group_name')->type('text'),
            Builder::field('create_time')->type('datetime')->sorter(true),
            Builder::field('status')->type('switch')->data($addonData['status']),
            Builder::field('trash')->type('trash'),
            Builder::field('actions')->data([
                Builder::button('edit')->type('primary')->call('page')->uri('/api/groups/:id'),
                Builder::button('delete')->type('default')->call('delete')->uri('/api/groups/delete')->method('post'),
            ]),
        ];

        return Builder::page('group-list')
                        ->type('basic-list')
                        ->searchBar(true)
                        ->tableColumn($tableColumn)
                        ->tableToolBar($tableToolBar)
                        ->batchToolBar($batchToolBar)
                        ->toArray();
    }

    // Accessor

    // Mutator

    // Searcher
    public function searchGroupNameAttr($query, $value)
    {
        $query->where('group_name', 'like', '%' . $value . '%');
    }
}
