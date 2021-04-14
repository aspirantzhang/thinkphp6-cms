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
            Builder::field('group_name', 'Group Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('rules', 'Rules')->type('tree')->data($addonData['rules']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset', 'Reset')->type('dashed')->call('reset'),
            Builder::button('cancel', 'Cancel')->type('default')->call('cancel'),
            Builder::button('submit', 'Submit')->type('primary')->call('submit')->uri('/api/groups')->method('post'),
        ];

        return Builder::page('group-add', 'Group Add')
                        ->type('page')
                        ->tab('basic', 'Basic', $basic)
                        ->action('actions', 'Actions', $action)
                        ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('group_name', 'Group Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('rules', 'Rules')->type('tree')->data($addonData['rules']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset', 'Reset')->type('dashed')->call('reset'),
            Builder::button('cancel', 'Cancel')->type('default')->call('cancel'),
            Builder::button('submit', 'Submit')->type('primary')->call('submit')->uri('/api/groups/' . $id)->method('put'),
        ];

        return Builder::page('group-edit', 'Group Edit')
                        ->type('page')
                        ->tab('basic', 'Basic', $basic)
                        ->action('actions', 'Actions', $action)
                        ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('add', 'Add')->type('primary')->call('modal')->uri('/api/groups/add'),
            Builder::button('reload', 'Reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [
            Builder::button('delete', 'Delete')->type('danger')->call('delete')->uri('/api/groups/delete')->method('post'),
            Builder::button('disable', 'Disable')->type('default')->call('function')->uri('batchDisableHandler'),
        ];
        if ($this->isTrash($params)) {
            $batchToolBar = [
                Builder::button('deletePermanently', 'Delete Permanently')->type('danger')->call('deletePermanently')->uri('/api/groups/delete')->method('post'),
                Builder::button('restore', 'Restore')->type('default')->call('restore')->uri('/api/groups/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('group_name', 'Group Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
            Builder::field('actions', 'Actions')->data([
                Builder::button('edit', 'Edit')->type('primary')->call('page')->uri('/api/groups/:id'),
                Builder::button('delete', 'Delete')->type('default')->call('delete')->uri('/api/groups/delete')->method('post'),
            ]),
        ];

        return Builder::page('group-list', 'Group List')
                        ->type('basicList')
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
