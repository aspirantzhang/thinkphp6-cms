<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\TPAntdBuilder\Builder;
use app\api\service\AuthGroup as AuthGroupService;

class Admin extends Common
{
    // Allow fields
    protected $readonly = ['id', 'admin_name'];
    protected $unique = [ 'admin_name' => 'Admin Name' ];
    protected $titleField = 'admin_name';

    public $allowHome = ['groups', 'admin_name', 'display_name'];
    public $allowList = ['groups', 'admin_name', 'display_name'];
    public $allowRead = ['admin_name', 'display_name'];
    public $allowSave = ['admin_name', 'password', 'groups', 'display_name'];
    public $allowUpdate = ['password', 'display_name', 'groups'];
    public $allowSearch = ['groups', 'admin_name', 'display_name'];

    public function setAddonData()
    {
        return [
            'groups' => (new AuthGroupService())->treeDataAPI(['status' => 1]),
        ];
    }

    // Relation
    public function groups()
    {
        return $this->belongsToMany(AuthGroupService::class, 'auth_admin_group', 'group_id', 'admin_id');
    }

    public function addBuilder($addonData = [])
    {
        $basic = [
            Builder::field('admin.admin_name')->type('input'),
            Builder::field('admin.password')->type('password'),
            Builder::field('admin.display_name')->type('input'),
            Builder::field('admin.groups')->type('tree')->data($addonData['groups']),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/admins')->method('post'),
        ];

        return Builder::page('admin.admin-add')
                        ->type('page')
                        ->tab('basic', $basic)
                        ->action('actions', $action)
                        ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('admin.admin_name')->type('input')->editDisabled(true),
            Builder::field('admin.display_name')->type('input'),
            Builder::field('admin.groups')->type('tree')->data($addonData['groups']),
            Builder::field('create_time')->type('datetime'),
            Builder::field('update_time')->type('datetime'),
            Builder::field('status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset')->type('dashed')->call('reset'),
            Builder::button('cancel')->type('default')->call('cancel'),
            Builder::button('submit')->type('primary')->call('submit')->uri('/api/admins/' . $id)->method('put'),
        ];

        return Builder::page('admin.admin-edit')
                        ->type('page')
                        ->tab('basic', $basic)
                        ->action('actions', $action)
                        ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('add')->type('primary')->call('modal')->uri('/api/admins/add'),
            Builder::button('reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [
            Builder::button('delete')->type('danger')->call('delete')->uri('/api/admins/delete')->method('post'),
            Builder::button('disable')->type('default')->call('disable')->uri('/api/admins/delete')->method('post'),
        ];
        if ($this->isTrash($params)) {
            $batchToolBar = [
                Builder::button('deletePermanently')->type('danger')->call('deletePermanently')->uri('/api/admins/delete')->method('post'),
                Builder::button('restore')->type('default')->call('restore')->uri('/api/admins/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('admin.admin_name')->type('input'),
            Builder::field('admin.groups')->type('tree')->data($addonData['groups'])->hideInColumn(true),
            Builder::field('admin.display_name')->type('input'),
            Builder::field('create_time')->type('datetime')->sorter(true),
            Builder::field('status')->type('switch')->data($addonData['status']),
            Builder::field('trash')->type('trash'),
            Builder::field('actions')->data([
                Builder::button('edit')->type('primary')->call('modal')->uri('/api/admins/:id'),
                Builder::button('delete')->type('default')->call('delete')->uri('/api/admins/delete')->method('post'),
            ]),
        ];
        if ($this->isTrash($params)) {
            $tableColumn = [
                Builder::field('admin.admin_name')->type('input'),
                Builder::field('admin.groups')->type('tree')->data($addonData['groups'])->hideInColumn(true),
                Builder::field('admin.display_name')->type('input'),
                Builder::field('delete_time')->type('datetime')->sorter(true),
                Builder::field('status')->type('switch')->data($addonData['status']),
                Builder::field('trash')->type('trash'),
                Builder::field('actions')->data([
                    Builder::button('restore')->type('default')->call('restore')->uri('/api/admins/restore')->method('post'),
                ]),
            ];
        }

        return Builder::page('admin.admin-list')
                        ->type('basic-list')
                        ->searchBar(true)
                        ->tableColumn($tableColumn)
                        ->tableToolBar($tableToolBar)
                        ->batchToolBar($batchToolBar)
                        ->toArray();
    }

    // Accessor

    // Mutator
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    // Searcher
    public function searchAdminNameAttr($query, $value)
    {
        $query->where('admin_name', 'like', '%' . $value . '%');
    }

    public function searchDisplayNameAttr($query, $value)
    {
        $query->where('display_name', 'like', '%' . $value . '%');
    }

    public function searchGroupsAttr($query, $value)
    {
        if ($value) {
            $group = new AuthGroupService();
            $adminIDs = $group->getIDsByRelationIDsAPI((array)explode(',', $value), 'admins');
            $query->whereIn('id', $adminIDs);
        }
    }
}
