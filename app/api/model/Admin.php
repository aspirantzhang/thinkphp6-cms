<?php

declare(strict_types=1);

namespace app\api\model;

use aspirantzhang\TPAntdBuilder\Builder;
use app\api\service\AuthGroup as AuthGroupService;

class Admin extends Common
{
    // Allow fields
    protected $readonly = ['id', 'username'];
    protected $unique = [ 'username' => 'Username' ];

    public $allowHome = ['groups', 'username', 'display_name'];
    public $allowList = ['groups', 'username', 'display_name'];
    public $allowRead = ['username', 'display_name'];
    public $allowSave = ['username', 'password', 'groups', 'display_name'];
    public $allowUpdate = ['password', 'display_name', 'groups'];
    public $allowSearch = ['groups', 'username', 'display_name'];

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
            Builder::field('username', 'Username')->type('text'),
            Builder::field('password', 'Password')->type('text'),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('groups', 'Group')->type('tree')->data($addonData['groups']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset', 'Reset')->type('dashed')->call('reset'),
            Builder::button('cancel', 'Cancel')->type('default')->call('cancel'),
            Builder::button('submit', 'Submit')->type('primary')->call('submit')->uri('/api/admins')->method('post'),
        ];

        return Builder::page('admin-add', 'Admin Add')
                        ->type('page')
                        ->tab('basic', 'Basic', $basic)
                        ->action('actions', 'Actions', $action)
                        ->toArray();
    }

    public function editBuilder($id, $addonData = [])
    {
        $basic = [
            Builder::field('username', 'Username')->type('text')->editDisabled(true),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('groups', 'Group')->type('tree')->data($addonData['groups']),
            Builder::field('create_time', 'Create Time')->type('datetime'),
            Builder::field('update_time', 'Update Time')->type('datetime'),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
        ];
        $action = [
            Builder::button('reset', 'Reset')->type('dashed')->call('reset'),
            Builder::button('cancel', 'Cancel')->type('default')->call('cancel'),
            Builder::button('submit', 'Submit')->type('primary')->call('submit')->uri('/api/admins/' . $id)->method('put'),
        ];

        return Builder::page('admin-edit', 'Admin Edit')
                        ->type('page')
                        ->tab('basic', 'Basic', $basic)
                        ->action('actions', 'Actions', $action)
                        ->toArray();
    }

    public function listBuilder($addonData = [], $params = [])
    {
        $tableToolBar = [
            Builder::button('add', 'Add')->type('primary')->call('modal')->uri('/api/admins/add'),
            Builder::button('pageAdd', 'Page add')->type('default')->call('page')->uri('/api/admins/add'),
            Builder::button('reload', 'Reload')->type('default')->call('reload'),
        ];
        $batchToolBar = [
            Builder::button('delete', 'Delete')->type('danger')->call('delete')->uri('/api/admins/delete')->method('post'),
            Builder::button('disable', 'Disable')->type('default')->call('disable')->uri('/api/admins/delete')->method('post'),
        ];
        if ($this->isTrash($params)) {
            $batchToolBar = [
                Builder::button('deletePermanently', 'Delete Permanently')->type('danger')->call('deletePermanently')->uri('/api/admins/delete')->method('post'),
                Builder::button('restore', 'Restore')->type('default')->call('restore')->uri('/api/admins/restore')->method('post'),
            ];
        }
        $tableColumn = [
            Builder::field('username', 'Username')->type('text'),
            Builder::field('groups', 'Groups')->type('tree')->data($addonData['groups'])->hideInColumn(true),
            Builder::field('display_name', 'Display Name')->type('text'),
            Builder::field('create_time', 'Create Time')->type('datetime')->sorter(true),
            Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
            Builder::field('trash', 'Trash')->type('trash'),
            Builder::field('actions', 'Actions')->data([
                Builder::button('edit', 'Edit')->type('primary')->call('modal')->uri('/api/admins/:id'),
                Builder::button('pageEdit', 'Page edit')->type('default')->call('page')->uri('/api/admins/:id'),
                Builder::button('delete', 'Delete')->type('default')->call('delete')->uri('/api/admins/delete')->method('post'),
            ]),
        ];
        if ($this->isTrash($params)) {
            $tableColumn = [
                Builder::field('username', 'Username')->type('text'),
                Builder::field('groups', 'Groups')->type('tree')->data($addonData['groups'])->hideInColumn(true),
                Builder::field('display_name', 'Display Name')->type('text'),
                Builder::field('delete_time', 'Delete Time')->type('datetime')->sorter(true),
                Builder::field('status', 'Status')->type('switch')->data($addonData['status']),
                Builder::field('trash', 'Trash')->type('trash'),
                Builder::field('actions', 'Actions')->data([
                    Builder::button('restore', 'Restore')->type('default')->call('restore')->uri('/api/admins/restore')->method('post'),
                ]),
            ];
        }

        return Builder::page('admin-list', 'Admin List')
                        ->type('basicList')
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
    public function searchUsernameAttr($query, $value)
    {
        $query->where('username', 'like', '%' . $value . '%');
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
