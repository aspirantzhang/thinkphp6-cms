<?php

declare(strict_types=1);

namespace app\backend\model;

use app\backend\service\AuthRule;
use app\backend\service\Admin;
use aspirantzhang\TPAntdBuilder\Builder;

class AuthGroup extends Common
{
    protected $deleteTime = 'delete_time';
    protected $readonly = ['id'];
    
    // Whitelist Fields Home/List/Sort/Read/Save/Update/Search etc...
    public $allowHome = ['sort', 'order', 'page', 'per_page', 'id', 'parent_id', 'name', 'rules', 'status', 'create_time'];
    public $allowList = ['id', 'parent_id', 'name', 'rules', 'status', 'create_time'];
    public $allowRead = ['id', 'parent_id', 'name', 'rules', 'status', 'create_time', 'update_time'];
    public $allowSort = ['sort', 'order', 'id', 'create_time'];
    public $allowSave = ['parent_id', 'name', 'rules', 'status'];
    public $allowUpdate = ['id', 'parent_id', 'rules', 'name', 'status', 'create_time'];
    public $allowSearch = ['id', 'parent_id', 'rules','name', 'status', 'create_time'];

    // Relation
    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'auth_admin_group', 'admin_id', 'group_id');
    }

    public function buildAdd($addonData = [])
    {
        $pageLayout = [
            Builder::field('name', 'Group Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('rules', 'Rules')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/groups')
                        ->method('post'),
            ]),
        ];

        return Builder::page('Add New Group')
            ->type('page')
            ->layout($pageLayout);
    }

    
    public function buildEdit($id, $addonData = [])
    {
        $pageLayout = [
            Builder::field('name', 'Group Name')->type('text'),
            Builder::field('parent_id', 'Parent')->type('parent')->data($addonData['parent_id']),
            Builder::field('rules', 'Rules')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::actions([
                Builder::button('Reset')->type('dashed')->action('reset'),
                Builder::button('Cancel')->type('default')->action('cancel'),
                Builder::button('Submit')->type('primary')->action('submit')
                        ->uri('/backend/groups/' . $id)
                        ->method('put'),
            ]),
        ];

        return Builder::page('Group Edit')
            ->type('page')
            ->layout($pageLayout);
    }

    public function buildList($addonData = [])
    {
        $tableToolBar = [
            Builder::button('Add')->type('primary')->action('modal')->uri('/backend/groups/add'),
            Builder::button('Full page add')->type('default')->action('page')->uri('/backend/groups/add'),
            Builder::button('Reload')->type('default')->action('reload'),
        ];
        $batchToolBar = [
            Builder::button('Delete')->type('danger')->action('batchDelete')
                    ->uri('/backend/groups/batch-delete')
                    ->method('delete'),
            Builder::button('Disable')->type('default')->action('function')->uri('batchDisableHandler'),
        ];
        $tableColumn = [
            Builder::field('name', 'Group Name')->type('text'),
            Builder::field('rules', 'Rules')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('tag')->data($addonData['status']),
            Builder::actions([
                Builder::button('Edit')->type('primary')->action('modal')
                        ->uri('/backend/groups'),
                Builder::button('Full page edit')->type('default')->action('page')
                        ->uri('/backend/groups'),
                Builder::button('Delete')->type('default')->action('delete')
                        ->uri('/backend/groups')
                        ->method('delete'),
            ])->title('Action'),
        ];

        return Builder::page('Group List')
            ->type('basicList')
            ->searchBar(true)
            ->tableColumn($tableColumn)
            ->tableToolBar($tableToolBar)
            ->batchToolBar($batchToolBar);
    }

    // Accessor

    // Mutator

    // Searcher
    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name', 'like', '%' . $value . '%');
    }

    public function searchRulesAttr($query, $value, $data)
    {
        $query->where('rules', 'like', '%' . $value . '%');
    }

}
